//size canvas for device

function getWords(text){       
  var allWordsNoBreaks =  text.replace(/\r?\n|\r/g, " ");
  allWords = allWordsNoBreaks.split(' ');
  // console.log( allWords);
  // console.log('first word:' + allWords[1]);
  // var numwords = allWords.length;
  // console.log('num words:' + numwords);
  // num_pages = Math.floor(numwords / page_length);
  // console.log('num pages:' + num_pages);
}

// // if page_num = -100 select a random page in the range of 
// //possible pages for the current text. Not currently using this
 // function getPage(pageNum){
 //  if (pageNum == -100){
 //    pageNum = Math.floor(Math.random(num_pages) + 1)
 //  }
 // }
  // current_page = pageNum;

//   $current_page = $page_num;
//   if (($current_page < $num_pages) or ($current_page < 0)) {
//   // // prepare and output html of that page
//     echo "<div>";
//     echo "<p>";
//     $start = $page_num * $page_length;
//    // echo "start " . $start ."\n";
//     $words_out = 0;
//    // echo "page length " + $page_length;
//     while($words_out < $page_length){
//         echo "<span id=\"" . $words_out. "\">" . $words[$start + $words_out] . " </span>"; 
//         $words_out++;
//         if ($words_out % $linelength == 0) {
//           echo "<br>";
//         } 
//       }



// make a tile containing a word
function makeTile(context, newWord, leftX, topY, lineHeight) {
  newTile = {
    word: newWord,
    left: Math.ceil(leftX),
    top: topY,
    right: Math.ceil(leftX + (context.measureText(newWord)).width),
    bottom: topY + lineHeight,
    visible: true
  }
  // console.log("MADE tile"  + ' word: ' + newTile.word  + ' left: ' + newTile.left + ' right: ' + newTile.top + ' top: ' + newTile.right + ' bottom: ' + newTile.bottom + ' ' + '\n');
    
  return newTile;
}

//place tile on canvas
function placeTiles(tiles,context) {
  for (var i = 0; i < tiles.length; i++) {
    // console.log('placing tile ' + i + ' ' + tiles[i].word);
    context.fillStyle = '#00c';
    context.textBaseline = "top";
    context.fillStyle = '#000';
    context.fillText(tiles[i].word, tiles[i].left, tiles[i].top);
  }
}

// add index of start of page - this should allow for differing sizes
// of pages to be displayed, just set current_page_pointer to word after
//last one displayed
// arrange the text in the array tiles so that it will fit on the canvas
function wrapTiles(tiles,context,  x, y, maxWidth,maxHeight, lineHeight) {
  // var words = text.split(' ');
  // position of current word in array of tiles
  // console.log("in WRAPTILES,  current_word_index is " + current_word_index);
  var tileIndex = 0;
  // x and y pos of current tile top leftf corner
  var xPos = x;
  var yPos = y;
  for (var n = current_word_index; n < allWords.length; n++) {
    // make a tile with the new word 
    var testTile = makeTile(context, (allWords[n] + ' '), xPos, yPos, lineHeight);
    // console.log("making tile" + n + testTile.word  + ' left ' + testTile.left + ' right ' + testTile.top + ' top ' + testTile.right + ' bottom' + testTile.bottom + ' ' + '\n');
    //if tileN.right is further over than max width
    if (testTile.right > maxWidth && n > 0) {
      //check word isn't ridiculously long and takes up more than a line
      if (testTile.right - testTile.left > maxWidth) {
        console.log("Error, word too long:" + testTile.word);
      } else {
        //move down a line and move x pointer back to start of line
        //change so that we check lineHeight isn't outside canvas
        // break out of loop if it is
        yPos += lineHeight;
        // console.log("INCR ypos now equals: " + yPos);
        if (yPos > maxHeight){
          current_word_index = n;
           if (page_length == 0 && current_word_index > 0) {
            page_length = current_word_index;
            num_pages = Math.ceil(allWords.length / page_length);
            console.log("WRAPPED page length: " + page_length + ' num pages: ' + num_pages);
           }
          // console.log("TOO BIG: ypos now equals: " + yPos);
            console.log("WRAPPED word INDEX: " + current_word_index);
            console.log("WRAPPED page length: " + page_length );
          break;
        }
        xPos = x;
        tile = makeTile(context, (allWords[n] + ' '), xPos, yPos, lineHeight);
        tiles[tileIndex] = tile;
        xPos = tile.right;
        tileIndex += 1;

      }
    } else {
      //make a tile and put it on this line
      tile = testTile;
      tiles[tileIndex] = tile;
      tileIndex += 1;
      // move x pointer along
      xPos = tile.right;
    }
  }
}


function adjustBlackout(tiles, context) {
  console.log("adjusting blackout");
  for (var n = 1; n < tiles.length; n++) {

    if (!tiles[n].visible) {
      console.log("adjusting invisible tile: " + n);
      if (tiles[n + 1].visible) {
        // retract the blackout tile to teh left slightly
        context.fillStyle = 'white';
        context.fillRect(tiles[n].left, tiles[n].top,
          (tiles[n].right - tiles[n].left + 0.5),
          tiles[n].bottom - tiles[n].top);
        context.fillRect(tiles[n + 1].left, tiles[n + 1].top,
          (tiles[n + 1].right - tiles[n + 1].left + 0.5),
          tiles[n + 1].bottom - tiles[n + 1].top);
        context.fillStyle = 'black';
        context.fillText(tiles[n + 1].word, tiles[n + 1].left, tiles[n + 1].top);

        context.fillRect(tiles[n].left, tiles[n].top,
          (tiles[n].right - tiles[n].left - 3),
          tiles[n].bottom - tiles[n].top);
      } else {
        // extend blackout to remove gaps between several black tiles
        context.fillStyle = 'black';
        context.fillRect(tiles[n].left, tiles[n].top,
          (tiles[n].right - tiles[n].left + 4.5),
          tiles[n].bottom - tiles[n].top);
      }

    }

  }


}
// toggle tiles between blacked-out and visible
function toggleTile(tiles,context, n) {
  if (tiles[n].visible == true) {
    // blackout tile
    tiles[n].visible = false;
    context.fillStyle = '#000';
    // 0.5 to adjust for weird pixel thing on canvas
    context.fillRect(tiles[n].left, tiles[n].top,
      (tiles[n].right - tiles[n].left),
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


function findTile(tiles,x, y) {
  var i = 0;
  var found = false;
  while (i < tiles.length && !found) {
    if (tiles[i].left <= x &&
      x <= tiles[i].right &&
      tiles[i].top <= y &&
      y <= tiles[i].bottom) {
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


function wordSelectStart( tiles,event) {
  var tileNum = findTile(tiles,event.pageX, event.pageY);
  console.log("in wordSelectStart, word: " + tileNum );
  dragstart = tileNum;
  dragging = true;
}


function wordSelectEnd(tiles, context) {
  console.log("in mouseup, coords: " + event.pageX + " " + event.pageY);
  var tileNum = findTile(tiles,event.pageX, event.pageY);
  console.log("in mouseup tile: " + tileNum);
  console.log("in mouseup dragging is: " + dragging + "dragstart is:" + dragstart);
  if (dragging) {
    console.log("in mouseup if dragging  ");
    if (tileNum != -1) {
      console.log("in mouseup about to do for loop  ");
      for (i = dragstart; i <= tileNum; i++) {
        console.log("in mouseup about to toggle tile: " + i);
        toggleTile(tiles, context, i);
      }

      dragging = false;

    }

  }
  // adjustBlackout(tiles, context);
}



function mouseClickEvent(e) {
  // alert("click event");
  mouseClick = true;
  if (e.offsetX) {
    mouseX = e.offsetX;
    mouseY = e.offsetY;
  } else if (e.layerX) {
    mouseX = e.layerX;
    mouseY = e.layerY;
  }
}


// console.log("space width is: " + (context.measureText(" ")).width);
// wrapTiles(context, text, x, y, maxWidth, lineHeight);
// placeTiles(context);
// context.fillStyle = '#000';
// console.log("tiles[1]" + tiles[1].word + tiles[1].top);
// console.log(tiles.length);