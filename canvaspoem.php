
<html>
 <head>
  <title>Poems in the Gaps</title>
  <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=EB+Garamond" >
  <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
  <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=no"/> 
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <link rel="stylesheet" type="text/css" href="./css/style.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
  <script src="js/canvasops2.js"></script> 
   <script src="js/canvas-scale.js"></script> 
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
  var topOffset = 0;
  var textSource = "";
  var fileSource = "";
  var tileColor = "#e6add8";


  // var canvas = document.getElementById('mycanvas');
  //   var context = canvas.getContext('2d');


// function printTilesToLog(tiles){
//    console.log("printing tiles");
//   for (var i = 0; i < 3; i++){
   
//     console.log("tile " + i + ' ' + tiles[i].word);
//   }
// }


  

  function initalize(){
    console.log("IN initialize");
    //standardize canvas sizes
    if (window.innerWidth < 480) {
      var width =Math.floor(window.innerWidth *96/100 );;
      var height = Math.floor(window.innerHeight * 75/100);
      console.log("small inner Height : " + window.innerHeight );
      var canvas = createHiDPICanvas(width, height,PIXEL_RATIO );
      console.log("Small Canvas Height : " + canvas.height );
      console.log("Small Canvas Width : " + canvas.width );
      
      canvas.id = "mycanvas";
      var canvasdiv = document.getElementById("canvasholder");
      canvasdiv.appendChild(canvas);
       var context = canvas.getContext('2d',{alpha: false});
      context.font = '16px Georgia';
      maxWidth = (canvas.width * 90 / 100)/PIXEL_RATIO;
      maxHeight = (canvas.height * 90 / 100)/PIXEL_RATIO;;
      console.log("Small Canvas maxHeight : " + maxHeight );
      console.log("Small Canvas maxWidth : " + maxWidth );
      leftOffset = 10;
      topOffset = 10;
      document.getElementById('mycanvas').style.marginLeft = leftOffset;
        // document.getElementById('mycanvas').style.margin-top = topOffset;
      console.log("maxHeight: " + maxHeight + "  maxWidth: " + maxWidth);
      context.font = '16px Georgia';
      } 
      
    else if (window.innerWidth < 768) {
        if (Math.floor(window.innerHeight* 75 / 100)< window.innerWidth){
          var width = Math.floor(window.innerHeight* 75 / 100);
        }
        else{
          var width = Math.floor(window.innerWidth* 85 / 100);
        }
      console.log("Med inner Height : " + window.innerHeight );
      var height = Math.floor(window.innerHeight* 75 / 100);
      console.log("Med Height : " + height );
     var canvas = createHiDPICanvas(width, height,PIXEL_RATIO );
      console.log("Med Canvas Height : " + canvas.height );
      canvas.id = "mycanvas";
      var canvasdiv = document.getElementById("canvasholder");
      canvasdiv.appendChild(canvas);
      var context = canvas.getContext('2d',{alpha: false});
      
      console.log('width med' + canvas.width);
      console.log('height med' + canvas.height);
      maxWidth = Math.floor(canvas.width * 95 / 100);
      maxHeight = Math.floor(canvas.height * 95 / 100);
      leftOffset = (window.innerWidth - canvas.width)/2;
      topOffset = 30;
       document.getElementById('mycanvas').style.marginLeft = leftOffset;
      console.log("maxHeight: " + maxHeight + "  maxWidth: " + maxWidth);
      context.font = '18px Georgia';
      } 
    else {
        if (Math.floor(window.innerHeight* 75 / 100)< window.innerWidth){
          var width = Math.floor(window.innerHeight* 95 / 100);
        }
        else{
          var width = Math.floor(window.innerWidth* 95 / 100);
        }
        console.log("Big inner Height : " + window.innerHeight );
      var height = Math.floor(window.innerHeight* 80 / 100);
      console.log("Big Height : " + height );
      var canvas = createHiDPICanvas(width, height,PIXEL_RATIO );
      console.log(" Big Canvas Height : " + canvas.height );
      canvas.id = "mycanvas";
      var canvasdiv = document.getElementById("canvasholder");
      canvasdiv.appendChild(canvas);
      var context = canvas.getContext('2d',{alpha: false});
      maxWidth = Math.floor(canvas.width * 95 / 100);
      maxHeight = Math.floor(height * 95 / 100);
      leftOffset = (window.innerWidth - canvas.width)/2;
      topOffset = 30;
      document.getElementById('mycanvas').style.marginLeft = leftOffset;
      console.log("maxHeight big " + maxHeight + "maxWidth big: " + maxWidth);
      context.font = '18px Georgia';
      }
    canvas.addEventListener("click", mouseClickEvent, false);
    canvas.addEventListener('mousedown', wordSelectStart.bind(null, leftOffset), false);
    canvas.addEventListener('mouseup', wordSelectEnd.bind(null, context, leftOffset), false);
    canvas.addEventListener('touchstart', function(e){
       e.preventDefault();
        touch = e.changedTouches[0] // reference first touch point for this event
        var x = touch.clientX;
        var y = touch.clientY;
        console.log("in touch START touchobj x: " + x + "in touch Start touchobj y: " + y);
       
        wordTouchStart(leftOffset, x, y);
    }, false);

    canvas.addEventListener('touchmove', function(e){
          e.preventDefault();
          // reference first touch point for this event
        // console.log("touchobj" + touchobj);
        // var touchx = touchobj.clientX;
        console.log("touchobj X" + e.changedTouches[0].clientX);
    
        // wordSelectStart(leftOffset, e);
    }, false);
     canvas.addEventListener('touchend', function(e){
      e.preventDefault();
        touchobj = e.changedTouches[0] // reference first touch point for this event
        console.log("touchobj" + touchobj);
        var x = touchobj.clientX;
        var y = touchobj.clientY;
        console.log("in touch End touchobj X" + x + "in touch End touchobj y " + y);
        e.preventDefault();
        // wordTouchEnd(context, leftOffset,x, y, e);
        wordTouchEnd(context, leftOffset,x, y);
    }, false);
      
  }


  function displayVals() {
    console.log("IN displayVals");
    var xInitial = 10;
    var yInitial = 0;

    // var lineIndex = new Array();
    var lineHeight = 30;
    // var dragstart = 0;
    // var dragging = false;
    var canvas = document.getElementById('mycanvas');
    var context = canvas.getContext('2d',{alpha: false});
    //get rid of any previous stuff on the canvas when moving page (hopefully)
    context.clearRect(0,0, canvas.width, canvas.height);
    // context.fillStyle = '#add8e6';
    context.fillStyle = 'white';
    context.fillRect(0,0,canvas.width,canvas.height );
    context.fillStyle = 'black';
    tiles = [];
    // var xInitial = Math.floor((canvas.width - maxWidth) / 2); 
    console.log('x initial val: ' + xInitial);
    var yInitial = 0;
    console.log('y initial val: ' + yInitial);
    // var text = '';
    // var text_name = document.getElementById(textSource).value;
    // console.log("TEXTname is " + text_name);
    // var spacewidth = (context.measureText(" ")).width;
    //add a bit here to only do the ajax call if you don't already have the text
    //from an upload. this should really go into Initialize.
     //This is the bit we need to call every time, regardless
        // of how we got the text, so above should go in initialize

        wrapTiles(context, xInitial, yInitial, lineHeight);
        placeTiles(context);
      //  console.log("printing TILES n DONE");
      //  printTilesToLog();
      //   // console.log("DONE page length: " + page_length );
        console.log("DONE PAGE LOADED" );
      }



function getSelectedText(text_name){
    
    console.log("in getSelectedText text_name is: " + text_name);
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
       
        text = data;
        // console.log("Beginning of done text: " +text.substr(0,100));
        // console.log("In read Select text name: " + text_name);
        getWords(text);
        // console.log("In ready Select third word: " + allWords[2]);
        // console.log("In ready Select current_word_index: " + current_word_index);
        $(".gettext").hide();
        $(".makepoem").show();
        initalize();
        displayVals();
        
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
        $(".gettext").show();
        $(".fict").hide();
        $(".nfict").hide();
        $(".polit").hide();
        $(".uplo").hide();
        $(".paste").hide();
        $(".makepoem").hide();

        // initalize();
        // displayVals(0);
    });
 
 $(document).ready(function(){
    $(window).scrollTop(0);
}); 

//deal with uploading a user text file
 // Callback from a <input type="file" onchange="onChange(event)">
function onChange(event) {
  var file = event.target.files[0];
  var reader = new FileReader();
  console.log('file uploading');
  reader.onload = function(event) {
    // The file's text will be printed here
    var alltext = event.target.result;
    console.log(alltext);
    getWords(alltext);
    console.log("In onChange Second word: " + allWords[1]);

    current_word_index = 0;

    $(".uplo").hide();
    $(".makepoem").show();

    initalize();
     displayVals();
  };
  reader.readAsText(file);
}

function hideselect(){
   $(".landing").hide();
        $(".fict").hide();
        $(".nfict").hide();
        $(".polit").hide();
        $(".uplo").hide();
        $(".paste").hide();
        $(".makepoem").show();
}


//display new text from the beginning when selected
  $(document).ready(function(){
      $( "select" ).change(function(event){
        current_word_index = 0;
        textSource = event.target.id;
        console.log("select changed, textSource is " + textSource);
        text_name = document.getElementById(textSource).value;
        
        getSelectedText(text_name);
        setTimeout(hideselect, 500);
        // hideselect();
         });
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
             
            displayVals();
            }
          }
        else if(this.id == 'randbutton'){//rand button
            current_word_index = Math.floor(Math.random() * allWords.length);
            current_page = Math.floor(current_word_index / page_length);
            displayVals();
        }
        else if(this.id == 'nextbutton'){ //next button
          current_word_index = current_word_index +1;
          current_page += 1;
          displayVals();
    }

    else if(this.id == 'loadfict'){ //user wants fiction
      console.log("loading fiction choices")
          $(".landing").hide();
        $(".fict").show();
        $(".nfict").hide();
        $(".polit").hide();
        $(".uplo").hide();
        $(".paste").hide();
        $(".makepoem").hide();
    }
        else if(this.id == 'loadnfict'){ //user wants fiction
              console.log("loading nfiction choices")
                  $(".landing").hide();
                $(".fict").hide();
                $(".nfict").show();
                $(".polit").hide();
                $(".uplo").hide();
                $(".paste").hide();
                $(".makepoem").hide();
            }
        else if(this.id == 'loadpol'){ //user wants fiction
              console.log("loading fiction choices")
                  $(".landing").hide();
                $(".fict").hide();
                $(".nfict").hide();
                $(".polit").show();
                $(".uplo").hide();
                $(".paste").hide();
                $(".makepoem").hide();
            }
        else if(this.id == 'loaduplo'){ //user wants fiction
              console.log("loading upload choices")
                  $(".landing").hide();
                $(".fict").hide();
                $(".nfict").hide();
                $(".polit").hide();
                $(".uplo").show();
                $(".paste").hide();
                $(".makepoem").hide();
            }
            else if(this.id == 'loadpast'){ //user wants fiction
              console.log("loading pasted choices")
                  $(".landing").hide();
                $(".fict").hide();
                $(".nfict").hide();
                $(".polit").hide();
                $(".uplo").hide();
                $(".paste").show();
                $(".makepoem").hide();
            }

            else if(this.id == 'pastebutton'){ //user wants fiction
              console.log("loading pasted choices")
              var alltext = document.getElementById('pastedtext').value;
              console.log(alltext);
              getWords(alltext);
              console.log("In button is pastebutton 2nd word: " + allWords[1]);

              current_word_index = 0;

              // $(".uplo").hide();
              // $(".makepoem").show();

              initalize();
               displayVals();
                  $(".landing").hide();
                $(".fict").hide();
                $(".nfict").hide();
                $(".polit").hide();
                $(".uplo").hide();
                $(".paste").hide();
                $(".makepoem").show();
            }
        else { //home
            location.reload();

        }

   });
  });


 </script>


 </head>
 <body>
  <div class="landing">
    <div class="header-image">

      <img src="img/poemsheader.jpg" class="img-responsive">

   
    <br/>

      <p>
        Make poems from longer texts by choosing which words to show and which to hide. 
</p>
      
        <p>
          Choose one of the categories below to get a base text:

        </p>
<br/>


        <div class="container-fluid">
          <div class="row">
        <button type="button" class="btn btn-primary btn-block" id="loadfict">Fiction</button>
             <button type="button" class="btn btn-primary btn-block" id="loadnfict">Non-Fiction</button>
             <button type="button" class="btn btn-primary btn-block" id="loadpol">Politics</button>
             <button type="button" class="btn btn-primary btn-block" id="loaduplo">Upload File</button>
             <button type="button" class="btn btn-primary btn-block" id="loadpast">Paste Text</button>
         </div>
      </div>


</div>
 </div>

<!-- first "page", get the user's selected text  -->
<div class="fict">
  <center>
  <div class="header-image">
    
    <img src="img/poemsheader.jpg" class="img-responsive">
    
</div>

    <div class="row justify-content-center " id="fiction">
      
      <div class="col justify-content-center">
        <select class="selectpicker" id="ddfic" >
        <option value ="instruction" selected > -- Please select --</option>
          <option value="frank">Frankenstein</option>
          <option value="super">Astounding Stories</option>
          <option value="dracula">Dracula</option>
          <option value="alice">Alice in Wonderland</option>
          <option value="moby">Moby Dick</option>
          <option value="pride">Pride and Prejudice</option>
          <option value="sherlock">Sherlock Holmes</option>
          
        </select> 
      </div>
    </div>
  </center>
  </div>

  
<div class="nfict">
  <center>
  <div class="header-image">
    
    <img src="img/poemsheader.jpg" class="img-responsive">
    
</div>

    <div class="row justify-content-center ">
      
      <div class="col justify-content-center">
        <select class="selectpicker" id="ddnfic">
          <option value ="instruction" selected > -- Please select --</option>
          <option value="tides">Time and Tide</option>
          <option value="dream">Dream Psychology</option>
          <option value="music">Shakespeare & Music</option>
          <option value="unix">Unix Programming </option>
          <option value="alchemy">Story of Alchemy </option>
          <option value="airplane">History of the Airplane</option>
        </select> 
        <br/>
        <br/>
      </div>
    </div>
  </center>
  </div>

  <div class="polit">
  <center>
  <div class="header-image">
    
    <img src="img/poemsheader.jpg" class="img-responsive">
    
</div>

    <div class="row justify-content-center ">
      
      <div class="col justify-content-center">
      <select class="selectpicker" id="ddpol">
       <option value ="instruction" selected > -- Please select --</option>
        <option value="cons17">Conservative Manifesto 17</option>
        <option value="lab17">Labour Manifesto 17</option>
        <option value="lib17">Lib Dem Manifesto 17</option>
        <option value="snp17">SNP Manifesto 17</option>
        <option value="trumpin">Trump: Inauguration Speech</option>
        <option value="trumpcong">Trump: Speech to Congress</option>
        <option value="trumpcpac">Trump: CPAC Speech</option>
        <option value="trumphealth">Trump: Rep. Health Bill</option>
      </select> 
      <br/>
      <br/>
    </div>
  </div>
  </center>
  </div>


<div class="uplo">
  <center>
  <div class="header-image">
    
    <img src="img/poemsheader.jpg" class="img-responsive">
    
</div>

    <div class="row justify-content-center ">
      
      <div class="col justify-content-center">
  </div>
  <div class="col justify-content-right">

    <input  type="file" id="fileinput" onchange="onChange(event)"/>
  </div>
</div>
</center>
</div>

<div class="paste">
  <center>
  <div class="header-image">
    
    <img src="img/poemsheader.jpg" class="img-responsive">
    
</div>

    <div class="row justify-content-center ">
      
      <div class="col justify-content-center">
  </div>
  <div class="col justify-content-right">
<textarea id="pastedtext" rows="10" cols="50">
Type or paste text here (there's no limit on length of text).
</textarea>
<div class="row justify-content-center ">
    <button class ="btn btn-primary btn-responsive btn-sm" id="pastebutton">
    Submit</button>
    </div>
  </div>
</div>
</center>
</div>


<!-- <div class="row" id="upload">
  <div class="col">
   type or paste some text:
  </div>
  <div class="col justify-content-right">
<textarea rows="4" cols="50">
</textarea>
    <input  type="button" id="fileinput" onchange="onChange(event)"/>
  </div>
</div> -->
<!-- </center> -->

</div> <!-- end gettext div -->



<!-- display canvas and next page buttons -->
<div class="makepoem">
  <div id="canvasholder">
  </div>
<!-- <canvas id="mycanvas"></canvas> -->

  <div id="controls">

   

    <button class="btn btn-primary btn-responsive btn-sm" id="prevbutton" type="button">
        prev 
      </button>

    <button class="btn btn-primary btn-responsive btn-sm" id="nextbutton" type="button">
        next 
    </button>

    <button class="btn btn-primary btn-responsive btn-sm" id="randbutton" type="button">
        random 
    </button>


<br/>

     <button class="btn btn-primary btn-responsive btn-sm" id="homebutton" type="button">
        home
      </button>

  </div>

</div> <!-- end makepoem "page" -->

 </body>
 
</html>