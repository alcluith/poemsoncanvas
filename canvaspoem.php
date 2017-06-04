
<html>
 <head>
  <title>Poems in the Gaps</title>
  <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=EB+Garamond" >
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <link rel="stylesheet" type="text/css" href="style.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
  <script src="js/canvasops2.js"></script> 
<!--   <script src="js/html2canvas.js"></script> -->
  <script>
  // index in word array of current page to display
  var current_word_index = 0;
  var current_page = 0;
  var text = "";
  // array containing all the words in the current text
  var allWords = new Array();
  var page_length = 0;
  var num_pages = 0;
  var maxWidth = 0;
  var maxHeight = 0;
  // var canvas = document.getElementById('mycanvas');
  //   var context = canvas.getContext('2d');

  function initalize(){
    
    var canvas = document.getElementById('mycanvas');
    var context = canvas.getContext('2d');
      canvas.width = window.innerWidth;
      canvas.height = window.innerHeight;
      console.log('width' + canvas.width);
      console.log('height' + canvas.height);
      // array of word tiles to put on the canvas
      // var tiles = new Array();
      context.fillStyle = 'blue';
      context.fillRect(0, 0, maxWidth, canvas.height);

      //is the user blacking out more than one word
      if (canvas.width < 480) {
        context.font = '18px Georgia';
        maxWidth = canvas.width * 95 / 100;
        maxHeight = canvas.height * 95 / 100;
        console.log("maxHeight: " + maxHeight + "maxWidth: " + maxWidth);
      } else if (canvas.width < 768) {
        context.font = '14px Georgia';
        maxWidth = canvas.width * 65 / 100;
        maxHeight = canvas.height * 75 / 100;
        console.log("maxHeight: " + maxHeight + "maxWidth: " + maxWidth);
      } else {
        context.font = '18px Georgia';
        maxWidth = canvas.width * 75 / 100;
        maxHeight = canvas.height * 75 / 100;
        console.log("maxHeight: " + maxHeight + "maxWidth: " + maxWidth);
      }
       
        
        
       
      // canvas.addEventListener("touchstart", wordSelectStart(tiles), false);
      // canvas.addEventListener('mouseup', wordSelectEnd, false);

      // canvas.addEventListener('touchend', function(e) {
      //   // prevent delay and simulated mouse events 
      //   e.preventDefault();
      //   wordSelectEnd();
      // });
      // 
      

  }

  function displayVals() {
    var xInitial = 0;
    var yInitial = 0;
    var tiles = new Array();
    var lineIndex = new Array();
    var lineHeight = 25;
    var dragstart = 0;
    var dragging = false;
    var canvas = document.getElementById('mycanvas');
    var context = canvas.getContext('2d');
     // context.fillStyle = 'blue';
     //  context.fillRect(0,0,canvas.width,canvas.height );
    
    // add event handlers to canvas
    canvas.addEventListener('mousedown', wordSelectStart.bind(null,tiles), false);
    canvas.addEventListener("touchstart", wordSelectStart.bind(null,tiles), false);
   canvas.addEventListener('mouseup', wordSelectEnd.bind(null, tiles,context), false);
    canvas.addEventListener('touchend', function(e) {
        // prevent delay and simulated mouse events 
        e.preventDefault();
        wordSelectEnd(tiles, context);
      });
    canvas.addEventListener("click", mouseClickEvent, false);

    var xInitial = (canvas.width - maxWidth) / 2;
    console.log('x initial val: ' + xInitial);
    var yInitial = 60;
    console.log('y initial val: ' + yInitial);
    var text = '';
    var text_name = $( "#dropdown" ).val();
    console.log(text_name);
    console.log("current_word_index is " + current_word_index);
    context.fillStyle = '#333';
    var spacewidth = (context.measureText(" ")).width;
    context.fillStyle = 'white';
    context.fillRect(0, 0, maxWidth, canvas.height);

// do the ajax get call to call display_page now
    $.ajax({
    // The URL for the request
    url: "display_page.php",
    data:{
      // page_num: this_page,
      current_text: text_name
    },
    // Whether this is a POST or GET request
    type: "GET",
    // The type of data we expect back
    dataType : "html",
  })

  // Code to run if the request succeeds (is done);
  // The response is passed to the function
    .done(function( data) {
      console.log("word INDEX: in done " + current_word_index);
      var current_text = document.getElementById("dropdown").value;
      // console.log("text chosen: " + current_text);
      alltext = data;
      // console.log("first line of page is:" + alltext.substr(current_word_index,current_word_index + 25));
      //////////////////////////////////////////////////
      //ADD CODE TO ONLY TRY AND SHOW ONE PAGE AT A TIME
      /////////////////////////////////////////////
      getWords(alltext, allWords);
      // text = getPage(alltext, ;
      // setText(data);
      // console.log('x  val in done: ' + xInitial);
       // console.log('y  val in done: ' + yInitial);
       wrapTiles(tiles,context, xInitial, yInitial, maxWidth, maxHeight,lineHeight);
       placeTiles(tiles,context);
        console.log("DONE page length: " + page_length );


  })

  // // Code to run if the request fails; the raw request and
  // // status codes are passed to the function
  .fail(function( xhr, status, errorThrown ) {
    alert( "Sorry, there was a problem!" );
    console.log( "Error: " + errorThrown );
    console.log( "Status: " + status );
    console.dir( xhr );
  })
  // Code to run regardless of success or failure;
  .always(function( xhr, status ) {
    console.log( "The request is complete!" );
  });

  }

// display a default page on load
 $( window ).on( "load", function() {
        console.log( "window loaded" );
        initalize();
        displayVals(0);
    });
  

//display new text from the beginning when selected
  $(document).ready(function(){
      $( "select" ).change(function(){
        current_word_index = 0;
        displayVals(current_word_index) });
  });

//display next page  in current text when clicked
  $(document).ready(function(){
    $( "button" ).click(function(){
        console.log("button clicked: " + this.id);
        if ((this.id == 'prevbutton')) {
          if (current_word_index > page_length){
            current_word_index = current_word_index - (2*page_length);
            if (current_word_index < 0) {
                current_word_index = 0;
            }
            if (current_page > 0) {
               current_page -= 1;
            }  
            console.log("PREV button clicked, word index now: " + current_word_index);
            console.log("PREV button clicked, page now: " + current_page);
            displayVals(current_word_index);
            }
          }
        else {
        current_word_index = current_word_index +1;
        current_page += 1;
        console.log("NEXT button clicked, word index now: " + current_word_index);
        console.log("NEXT button clicked, page now: " + current_page);
        
        displayVals(current_word_index);
    }
   });
  });

 </script>


 </head>
 <body>
<!--  <nav class="navbar navbar-inverse">
  <div class="container-fluid">
    <ul class="nav navbar-nav">
      <li class="active"><a href="">Poems in the Gaps</a></li>
      <li><a href="makeapoem.php">Make a poem</a></li>
      <li><a href="credits.html">About</a></li>
      <!-- <li><a href="#">Page 3</a></li> -->
    <!-- </ul>
  </div> -->
<!-- </nav>  -->

Select text to work with: 
  <select id="dropdown">
    <option value="tides">Time and Tide</option>
    <option value="frank">Frankenstein</option>
    <option value="dream">Dream Psychology</option>
    <option value="music">Shakespeare and Music</option>
    <option value="unix">Art of Unix Programming </option>
    <option value="alchemy">Story of Alchemy </option>
    <option value="super">Astounding Stories</option>
    
  </select>

<!-- <button id="randombutton" type="button">
    random page
  </button>
 -->

<button id="prevbutton" type="button">
    prev page
  </button>

<button id="nextbutton" type="button">
    next page
</button>


  <!-- display page -->

  <div id="textpage">

<canvas id="mycanvas"></canvas>

  </div>

 </body>
 
</html>