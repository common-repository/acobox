<?php
/*
Plugin Name: Acobox Free Pictures
Plugin URI: http://acobox.com
Description: Get free pictures that match and enhance your blog post, without searching, downloading, and uploading.
Author: Acosys Limited
Version: 2.0
Author URI: http://www.acosys.com
*/

// CONFIG SETTINGS

// words of three letters or less that should be included as search terms
$allowed = "'cat', 'dog'";

// words of more than three letters that should not be included as search terms
$notAllowed = "'with', 'that','more'";

//weighting for each type of content
$tagWeight = 3;
$titleWeight = 2;
$contentWeight = 1;
// END CONFIG SETTINGS


function acosys_add_acobox () {
global $allowed;
global $notAllowed;
global $tagWeight;
global $titleWeight;
global $contentWeight;
?>

<script type="text/javascript">

var allowed = new Array(<?php echo $allowed; ?>);
var notAllowed = new Array(<?php echo $notAllowed; ?>);

var tagWeight = <?php echo $tagWeight; ?>;
var titleWeight = <?php echo $titleWeight; ?>;
var contentWeight = <?php echo $contentWeight; ?>;

  function wc(str) {

       var sa = str.split(" ");
       var map = {};
       for (var i = 0; i < sa.length; i++) {
            var w = sa[i].toLowerCase();
            var count = map[w];
              if (count == null) count = 0;
              count++;
              map[w] = count;
            }
           return map;
      }


function startWatcher(){
  setInterval("installWatcher()", 2000);
}



var orgTags = null;

function installWatcher(){
var tagDiv = document.getElementById("tagchecklist");
var newTags = tagDiv.innerHTML;

  if (orgTags == null){
  orgTags = newTags;
  }


  if (newTags !== orgTags){
  orgTags = newTags;
  grabContent();
  }
}

// main function to grab image content based in user input

function grabContent(){

// grabbing just the words by removing stuff we don't need
titleDiv = document.getElementById('title');
rawTitleContent = titleDiv.value;
titleContent = rawTitleContent.replace(/(<([^>]+)>)/ig,"");

postDiv = document.getElementById('content');
rawPostContent = postDiv.value;
postContent = rawPostContent;
postContent = rawPostContent.replace(/(<([^>]+)>)/ig,"");

tagDiv = document.getElementById('tagchecklist');
rawTagContent = document.getElementById('tagchecklist').innerHTML;
tagContent = rawTagContent.replace(/(<([^>]+)>)/ig,"");
tagContent = tagContent.replace(/X&nbsp;/g,"");
tagContent = tagContent.replace("Tags used on this post:","");

var allContent = titleContent+postContent+tagContent;

if (allContent.length > 1){

var wordList = new Array();

var contentWords = new Array();
contentWords = postContent.split(" ");

for(i=0;i<contentWords.length; i++){

// checking against allowed / not allowed words
var thisWord = contentWords[i].toLowerCase();
  if( thisWord.length > 3 || allowed.toString().indexOf(thisWord) >= 0 ){

    if(notAllowed.toString().indexOf(thisWord) == -1){
     for(z=0; z<contentWeight; z++){
      wordList.push(thisWord);
    
     }
    }
  }
}


var tagWords = new Array();
tagWords = tagContent.split(" ");

for(i=0;i<tagWords.length; i++){

var thisWord = tagWords[i].toLowerCase();

  if( thisWord.length > 3 || allowed.toString().indexOf(thisWord) >= 0 ){

    if(notAllowed.toString().indexOf(thisWord) == -1){

    for(z=0; z<tagWeight; z++){
    wordList.push(thisWord);

     }
    
    }
  }
}


var titleWords = new Array();
titleWords = titleContent.split(" ");


for(i=0;i<titleWords.length; i++){

var thisWord = titleWords[i].toLowerCase();

  if( thisWord.length > 3 || allowed.toString().indexOf(thisWord) >= 0 ){

    if(notAllowed.toString().indexOf(thisWord) == -1){
    for(z=0; z<titleWeight; z++){

    wordList.push(thisWord);
 
     }
    }
  }
}


str = wordList.toString();

  function wc(str) {
            var sa = str.split(",");
            var map = {};
            for (var i = 0; i < sa.length; i++) {
                var w = sa[i].toLowerCase();
                var count = map[w];
                if (count == null) count = 0;
                count++;
                map[w] = count;
            }
            return map;
        }


      var map = wc(str);
            var ca = [];
            for (var i in map) {
                ca.push([map[i] , i]);
            }

    ca.sort(function(a, b) {
                return a[0] - b[0];
            });



    var sortedList = new Array();
    
            for (var j = 0; j < ca.length; j++) {
                sortedList.push(ca[j][1]);
            }
       


      if (sortedList.length > 0){

      var sortedListTop = new Array();
      sortedListTop = sortedList.slice((sortedList.length-5),sortedList.length);

      // put most weighted items first
      sortedListTop.reverse();


      var currentHTML = document.getElementById('acoSearchLinks').innerHTML;

      document.getElementById('acoSearchLinks').innerHTML = '<div style="padding: 4px; width: 112px; background: #990000; color: #fff;">Refreshing...</div>'+currentHTML;

      var url = '<?php bloginfo('url');?>/wp-content/plugins/acobox/results.php?list='+sortedListTop.toString();



      function httpResponse() {

          if (http.readyState == 4) {
          document.getElementById('acoSearchLinks').innerHTML = http.responseText;
          metaBoxDiv = document.getElementById('aco');
          metaBoxDiv.style.overflow = "auto";
          }
      }

      function getHTTPObject() {
        var xmlhttp;
        /*@cc_on
        @if (@_jscript_version >= 5)
          try {
            xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
          } catch (e) {
            try {
              xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            } catch (E) {
              xmlhttp = false;
            }
          }
        @else
        xmlhttp = false;
        @end @*/
        if (!xmlhttp && typeof XMLHttpRequest != 'undefined') {
          try {
            xmlhttp = new XMLHttpRequest();
          } catch (e) {
            xmlhttp = false;
          }
        }
        return xmlhttp;
      }
      var http = getHTTPObject();

      var nocache = Math.random();
      http.open("GET", url + '&nocache='+nocache, true);
      http.onreadystatechange = httpResponse;

      http.send(null);


      }



}


// set up listeners

var titleTxt = document.getElementById('title');
  if (titleTxt.addEventListener){
  titleTxt.addEventListener('blur',grabContent,false );
  }


var contentTxt = document.getElementById('content');
  if (contentTxt.addEventListener){
  contentTxt.addEventListener('keydown',stringCount,false );
  }


var strokeCount = 0;
function strokeCounter(){
strokeCount++;
  if (strokeCount == 200){
  grabContent();
  strokeCount = 0;
  }
}

}


var stringCount = contentTxt.value.length;

function stringCount(){
newStringCount = contentTxt.value.length;

  if (Math.abs(newStringCount - stringCount) >= 200){
  grabContent();
  stringCount = newStringCount;
  }
}

</script>


<div id="acoSearchLinks"></div>

<script language="JavaScript">

function addLoadEvent(func) {
  var oldonload = window.onload;
  if (typeof window.onload != 'function') {
    window.onload = func;
  } else {
    window.onload = function() {
      if (oldonload) {
        oldonload();
      }
      func();
    }
  }
  

}

addLoadEvent(grabContent);
addLoadEvent(startWatcher);

addLoadEvent(function() {
//grabContent();
})

</script>

<?php }

// adding box to WP-Admin pages
add_action('admin_menu', 'acoBoxAddCustomBox');

function acoboxAddCustomBox() {
    add_meta_box( 'aco', __( 'Acobox', 'myplugin_textdomain' ),'acosys_add_acobox', 'post', 'normal', 'high' );
}

?>