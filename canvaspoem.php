
<html>
 <head>
  <title>Poems in the Gaps</title>
  <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=EB+Garamond" >
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <link rel="stylesheet" type="text/css" href="./css/style.css">
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
  var tiles = new Array();
  var page_length = 0;
  var num_pages = 0;
  var maxWidth = 0;
  var maxHeight = 0;
  var leftOffset = 0;

  // var canvas = document.getElementById('mycanvas');
  //   var context = canvas.getContext('2d');


// function printTilesToLog(tiles){
//    console.log("printing tiles");
//   for (var i = 0; i < 3; i++){
   
//     console.log("tile " + i + ' ' + tiles[i].word);
//   }
// }
  function initalize(){
    
    var canvas = document.getElementById('mycanvas');
    var context = canvas.getContext('2d');
    canvas.width = canvas.width;
    // context = None;
      // canvas.width = window.innerWidth;
      // canvas.height = window.innerHeight;
      // console.log('width' + canvas.width);
      // console.log('height' + canvas.height);
      // array of word tiles to put on the canvas
      // var tiles = new Array();
      context.fillStyle = 'blue';
      context.fillRect(0, 0, maxWidth, canvas.height);

      //is the user blacking out more than one word
      if (window.innerWidth < 480) {
        context.font = '18px Georgia';

        maxWidth = canvas.width * 95 / 100;
        maxHeight = canvas.height * 95 / 100;
        // console.log("maxHeight small: " + maxHeight + "maxWidth: " + maxWidth);
      } else if (canvas.width < 768) {
     
        
        canvas.width = Math.floor(window.innerWidth* 65 / 100) ;
        canvas.height = Math.floor(window.innerHeight* 75 / 100);
        // console.log('width med' + canvas.width);
        // console.log('height med' + canvas.height);
        maxWidth = Math.floor(canvas.width * 95 / 100);
        maxHeight = Math.floor(canvas.height * 95 / 100);
        leftOffset = (window.innerWidth - canvas.width)/2;
        // console.log("LEFT Offset " + leftOffset);
         document.getElementById('mycanvas').style.marginLeft = leftOffset;
        // console.log("maxHeight med: " + maxHeight + "maxWidth: " + maxWidth);
        context.font = '18px Georgia';

      } else {
        context.font = '14px Georgia';
        canvas.width = window.innerWidth* 65 / 100 ;
        canvas.height = window.innerHeight* 75 / 100;
        // console.log('width big' + canvas.width);
        // console.log('height big' + canvas.height);
        maxWidth = canvas.width * 95 / 100;
        maxHeight = canvas.height * 95 / 100;
        // console.log("maxHeight: " + maxHeight + "maxWidth: " + maxWidth);
      }
      
  }


function printTilesToLog(){
   console.log("printing tiles");
  for (var i = 0; i < 3; i++){
    console.log("tile " + i + ' ' + tiles[i].word);
  }
}

// toggle tiles between blacked-out and visible
function toggleTile(context, n) {
  console.log("toggling tile " + n);
  if (tiles[n].visible == true) {
    // blackout tile
    tiles[n].visible = false;
    context.fillStyle = '#000';
    // 0.5 to adjust for weird pixel thing on canvas
    context.fillRect(tiles[n].left, tiles[n].top,
      (tiles[n].right - tiles[n].left -1.5),
      tiles[n].bottom - tiles[n].top);
                   
  } else {
    //reveal tile
    tiles[n].visible = true;
    context.fillStyle = '#fff';
    // 0.5 to adjust for weird pixel thing on canvas
    context.fillRect(tiles[n].left - 1.5, tiles[n].top,
      (tiles[n].right - tiles[n].left + 1),
      tiles[n].bottom - tiles[n].top);
    context.fillStyle = '#fff';
    context.fillStyle = '#000';
    context.fillText(tiles[n].word, tiles[n].left, tiles[n].top);
  }
}


function findTile(x, y, leftOffset) {
  var i = 0;
  var adjY = y - 30;
  var adjX = x - leftOffset;
  // console.log("FIND tile left offset = " + leftOffset);
  var found = false;
  while (i < tiles.length && !found) {
    if (tiles[i].left <= adjX &&
      adjX <= tiles[i].right &&
      tiles[i].top <= adjY &&
      adjY <= tiles[i].bottom) {
      console.log("tile found: " + tiles[i].word + i);
      found = true;
    } else {
      i += 1;
    }
  }
  if (!found) {
    console.log("tile not found");
    return -1;
  } else {
    return i;
  }
}



///////////////////
// Event Handlers
///////////////////


function wordSelectStart( leftOffset, event) {
  var tileNum = findTile(event.pageX, event.pageY, leftOffset);
  console.log("in MOUSEDOWN, tilenum: " + tileNum + ' ' + 'eventx:'  + event.pageX + ' ' + 'eventy:'  + event.pageY );
  console.log("in wordSelectSTART, word: " + tiles[tileNum].word + ' ' + 'word left:'  + tiles[tileNum].left + ' ' + 'tile right:'  + tiles[tileNum].right );
  printTilesToLog();
  dragstart = tileNum;
  dragging = true;
}

// problem with tiles is here
function wordSelectEnd( context, leftOffset) {
  console.log("in MOUSEUP, coords: " + event.pageX + " " + event.pageY);
  // console.log("printing tiles");
  printTilesToLog();
  var tileNum = findTile(event.pageX, event.pageY, leftOffset);
  console.log("in mouseup tile: " + tileNum);
  console.log("in mouseup DRAGGINg is: " + dragging + " dragstart is:" + dragstart);
  if (dragging) {
    // console.log("in mouseup if dragging  ");
    if (tileNum != -1) {
      // console.log("in mouseup about to do for loop  ");
      for (i = dragstart; i <= tileNum; i++) {
        // console.log("in mouseup about to toggle tile: " + i);
        toggleTile(context, i);
      //add another loop to adjust these tiles here
      // adjustBlackout(tiles, context);
      }

      dragging = false;

    }

  }
 
}



  function displayVals() {
    var xInitial = 0;
    var yInitial = 0;
    // var tiles = new Array();
    var lineIndex = new Array();
    var lineHeight = 30;
    var dragstart = 0;
    var dragging = false;

    var canvas = document.getElementById('mycanvas');
    var context = canvas.getContext('2d');
    //get rid of any previous stuff on the canvas when moving page (hopefully)
     context.clearRect(0,0, canvas.width, canvas.height);

     context.fillStyle = 'white';
      context.fillRect(0,0,canvas.width,canvas.height );
    clearTiles();
    // add event handlers to canvas
    canvas.addEventListener('mousedown', wordSelectStart.bind(null, leftOffset), false);
    // canvas.addEventListener("touchstart", wordSelectStart.bind(null,tiles,leftOffset), false );
   canvas.addEventListener('mouseup', wordSelectEnd.bind(null, context, leftOffset), false);
    // canvas.addEventListener('touchend', function(e) {
        // prevent delay and simulated mouse events 
      //   e.preventDefault();
      //   wordSelectEnd(tiles, context,leftOffset);
      // });
    // canvas.addEventListener("click", mouseClickEvent, false);

    var xInitial = Math.floor((canvas.width - maxWidth) / 2);
    // console.log('x initial val: ' + xInitial);
    var yInitial = 0;
    // console.log('y initial val: ' + yInitial);
    var text = '';
    var text_name = $( "#dropdown" ).val();
    // console.log(text_name);
    // console.log("current_word_index is " + current_word_index);
    context.fillStyle = '#333';
    var spacewidth = (context.measureText(" ")).width;
    // context.fillStyle = 'white';
    // context.fillRect(0, 0, maxWidth, canvas.height);

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
      getWords(alltext);
      // text = getPage(alltext, ;
      // setText(data);
      // console.log('x  val in done: ' + xInitial);
       // console.log('y  val in done: ' + yInitial);
       wrapTiles(context, xInitial, yInitial, maxWidth, maxHeight,lineHeight);
       placeTiles(context);
       console.log("printing TILES n DONE");
       printTilesToLog();
        // console.log("DONE page length: " + page_length );
        console.log("DONE PAGE LOADED" );
      //   context.fillStyle = 'blue';
      // context.fillRect(0, 0, maxWidth, canvas.height);



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
            // console.log("PREV button clicked, word index now: " + current_word_index);
            // console.log("PREV button clicked, page now: " + current_page);
             initalize();
            displayVals();
            }
          }
        else if(this.id == 'randbutton'){
            
            current_word_index = Math.floor(Math.random() * allWords.length);
            current_page = Math.floor(current_word_index / page_length);
            // console.log("RAND button clicked, word index now: " + current_word_index);
            // console.log("RAND button clicked, page now: " + current_page);
            initalize();
            displayVals();
        }
        else { //next button

          current_word_index = current_word_index +1;
          current_page += 1;
          // console.log("NEXT button clicked, word index now: " + current_word_index);
          // console.log("NEXT button clicked, page now: " + current_page);
           initalize();
          displayVals();
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



  <!-- display page -->
<canvas id="mycanvas"></canvas>

  <!-- <div id="textpage">


  </div> -->
  <br/>
<br/>
  <div id="choosetext">
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
<br/>
<br/>
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

<button id="randbutton" type="button">
    random page
</button>
</div>


 </body>
 
</html>