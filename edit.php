<?php 
session_start(); 
ob_start("ob_gzhandler"); 
 require_once("config/inc/connect.php");
 include("config/fn/lib.php"); 
 include("config/fn/fn.php"); 
 
 /// Authentication
 if(!isset($_GET['q']) or !isset($_SESSION['uid'])){
	echo 'Not allowed' ;
	exit();
 }

$qid = clean($_GET['q']);
$uid = Get_q_data($qid,'uid') ;

// If user didnt ask this question # Hacker Alert #Kill script
if($_SESSION['uid'] !== $uid){
	echo 'Nice try but you are still not allowed' ;
	Unset_Login_Params() ;
	exit();
}

 
?>


<!DOCTYPE html>
<html>
<head lang="en-US">
<meta charset="UTF-8">
<link rel="shortcut icon" href="img/icon.gif" />
<title>Edit Question - TheStudybox</title>
<meta name="robots" content="noindex, nofollow" />
<meta http-equiv="Content-Language" content="en-US">
<script src="js/jquery.js"></script> <!-- Jquery script -->
<script src="js/general.js"></script> <!-- Jquery script -->
<script src="js/lib.js"></script> 
<script type="text/javascript" src="js/tinymce/jscripts/tiny_mce/tiny_mce.js"> </script>

<!--[if IE 9]>
<link href="css/iefix.css" rel="stylesheet" type="text/css" />
<![endif]-->

<link href="css/general.css" rel="stylesheet" type="text/css" />
<link href="css/ask.css" rel="stylesheet" type="text/css" />



<script type="text/javascript"> 
 <!-- tinymce for textareas -->
tinyMCE.init({
        mode : "textareas",
        theme : "advanced",
		height : "300",
		width : "565" ,
        plugins : "emotions,spellchecker,advhr,insertdatetime,preview",
		//add paste plugin
plugins : 'paste',
//Keeps Paste Text feature active until user deselects the Paste as Text button
paste_text_sticky : true,
//select pasteAsPlainText on startup
setup : function(ed) {
    ed.onInit.add(function(ed) {
        ed.pasteAsPlainText = true;
    });
}, 
                
        // Theme options - button# indicated the row# only
        theme_advanced_buttons1 : "bold,italic,underline,|bullist,numlist,|sub,sup,|,charmap,emotionslink",
        theme_advanced_buttons2 : "",
        theme_advanced_buttons3 : "",      
        theme_advanced_toolbar_location : "top",
        theme_advanced_toolbar_align : "left",
        theme_advanced_statusbar_location : "",
        theme_advanced_resizing : false,
		content_css: "css/content.css",
});

/// TAG CREATION /////
$(document).ready(function() {
    var tagcloud = '.tagelements' ;
	
	// toggle visibility between span and text box //
	 $(tagcloud).bind('click', function(e){
	 $(this).hide() ;
	 $('#tags').show() ;
	 $('#tags').focus() ;
   	 });
	 
	 $('#tags').bind('blur', function(e){
	 $(this).hide() ;
	 
	 // tag cloud creation
  var keywords = $.trim($('#tags').val().replace( /[\s\n\r]+/g, ' ' )); // get all element box and clean
  var tagArray = keywords.split(','); // create tag array
	 // loop through array 
  var createdtags = '' ;
  var cleanvalue = '' ;
  for(var i=0; i<tagArray.length; i++){
  var value = tagArray[i];
  
  if(value != '' && value != ' ' ){
  
  if(cleanvalue != ''){
  cleanvalue += "," + value ;
  }
  else{
  cleanvalue += value ;
  }
  
  $('#tags').val(cleanvalue) ;
  
  createdtags += "<a href='javascript:void(0);'>" + value + "</a>" ;
  }
  
  }
  
	 $(tagcloud).html(createdtags) ;
	 $(tagcloud).show() ;
     });
	 
	 });

	  // DESIGNING TAGS
  $(document).ready(function (){
  
  $('#tagss').bind('keyup', function(e){
  var keywords = $(this).val();
  var tagArray = keywords.split(','); // create tag array
  
  // loop through array 
  for(var i=0; i<tagArray.length; i++){
  var value = tagArray[i];
  alert(i =") "+value);
  }
  
  });
  
  });
  
  // NOTIFICATION SPANS ///
  $(document).ready(function (){
  
  $('#tags').on('focus', function(){
  $('.searchResult').hide();
  });
  
  // Title tip
  $('#Title').bind('focus', function(e){
  $('.helptips .titletip').fadeIn('fast').css('display','block');
  });
  $('#Title').bind('blur', function(e){
  // show body tips after titletip fades out
  $('.helptips .bodytip').fadeIn('slow').css('display','block');
  });
  
  // Body tip
  $('#tags').bind('focus', function(e){
  // First hide body tip then show tagtip
    $('.helptips .tagtip').fadeIn('fast').css('display','block');
  });
  
  $('#tags').bind('blur', function(e){
  $('.helptips .bodytip').fadeIn('slow').css('display','block').delay(8000).fadeOut('fast');
  });
  
  /// ON Form Submission
  $('#askForm').on('submit', function(e){
  e.preventDefault();
  var err = '#askForm label';
$(err).removeAttr('class');

$('#askForm .req').each(function() {
	
	var content = $.trim($(this).val());
	var msg = $(this).attr('data-alert');
	
	if(content == ''){
		$(err).addClass('error').show().css('display','inline-block').html(msg);
		$(this).focus();
		exit();
	}
});

var question = tinyMCE.get('body').getContent();

var qlen = question.length ; 

if(qlen < 20){
		$(err).addClass('warning').show().css('display','inline-block').html("Your question is not up to standard");
		exit();
}

/// get all inputs
var title = $.trim($('#Title').val()) ;
var tags = $.trim($('#tags').val()) ;
var qid = $.trim($('#qid').val()) ;

var dataString = 'title=' +  title + '&question=' + question + '&tags=' + tags + '&qid=' + qid  ;
startLoader();
/// Ajax Process ///
$('#askForm :input').attr('disabled','disabled');

$.ajax({
url: 'config/ajax/ask_new_que.php',
data: 'action=SaveEdits&' + dataString,
success: function(returnedData){
$('#askForm :input').removeAttr('disabled');
endLoader();
eval(returnedData);
},
error: function () {
$('#askForm :input').removeAttr('disabled');
var err = '#askForm label' ;
$(err).removeAttr('class');
$(err).addClass('error').show().css('display','inline-block').html('677: Error Occured while updating. Retry');
history.go(-1);
}
});
//// End of Ajax Process

});
 
  });
</script>
<head>
<body>

<div class="container">

<div class="header">
<?php include("config/inc/header.php"); ?>
</div>

<input type="hidden" id="qid" value="<?php echo $qid ; ?>" />

<div class="wrapper">

<!--navigation  -->
<nav>
<ul>
<li><a href="questions.php">Questions</a></li>
<li><a href="unanswered.php">Unanswered</a></li>
<li><a href="ask.php">Ask Question</a></li>
<li><a href="notes.php">Notes</a></li>
</ul>
</nav>

<div class="ask">

<h2>Edit Question</h2>
<br>
<form action="#" id="askForm" method="post">
<table>
<tr>
<td><b class="hide">Title</b><input type="text" maxlength="100" data-alert="Title field is empty" name="Title" value="<?php echo Get_q_data($qid,'title'); ?>" placeholder="Title" id="Title" class="req" autocomplete="off" /></td>
</tr>
<tr>
<td><b class="hide">Body</b><div id="bodyContent"><textarea class="req" id="body" name="body" data-alert="no question yet" placeholder="body" ><?php echo html_entity_decode(Get_q_data($qid,'question')); ?></textarea></div></td>
</tr>
<tr><td></td></tr>
<tr>
<td><b class="hide">Subject Tag</b><input type="text" class="req" maxlength="50" data-alert="at least one tag is required"  placeholder="Subject Tag" name="tags" id="tags" autocomplete="on" value="<?php $tags = Get_q_data($qid,'tags'); echo $tags ; ?>"  /><span class="tagelements"><?php echo $tags ; ?></span></td>
</tr>
<tr>
<td><label style="display:none" class="success"></label></td>
</tr>
<tr>
<td><input type="submit" value="Post"/></td>
</tr>
</table>
</form>

</div>

<div class="helptips">
<span class="titletip">
Titles should be short , comprehensive and  should quickly 
give a hint of the question to be asked.
</span>
<span class="bodytip">
Question should be clear and conscise , avoid long stories
and go straight to the point. You can use the formating tools to 
format text and also the last "anchor" icon to insert special characters
if you need to. 
</span>
<span class="tagtip">
Enter the subject domain of your question. At least one subject is required and a maximum of 2.
</span>
</div>

<div class="clear"></div>
</div>

<div class="footer">
<?php include("config/inc/footer.php") ?>
</div>


</div>

</body>
</html> <?php ob_flush(); flush() ; ?>