//size canvas for device

function getWords(text){       
  var allWordsNoBreaks =  text.replace(/\r?\n|\r/g, " ");
  allWords = allWordsNoBreaks.split(' ');
  }


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
function placeTiles(context) {
  console.log('placing tiles ');
  for (var i = 0; i < tiles.length; i++) {
      
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
//USE TILE INDE HERE?
function wrapTiles(context,  x, y, maxWidth,maxHeight, lineHeight) {
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
            // console.log("WRAPPED page length: " + page_length + ' num pages: ' + num_pages);
           }
          // console.log("TOO BIG: ypos now equals: " + yPos);
            // console.log("WRAPPED word INDEX: " + current_word_index);
            // console.log("WRAPPED page length: " + page_length );
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


function adjustBlackout( context) {
  console.log("adjusting blackout");
  for (var n = 0 ; n < tiles.length ; n++) {

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
        context.fillRect(tiles[n].left -1, tiles[n].top,
          (tiles[n].right - tiles[n].left + 5.5),
          tiles[n].bottom - tiles[n].top);
      }

    }

  }


}

function clearTiles(){
  tiles = [];
  // for (var i = 0; i< tiles.length; i++){

  // }
}
// toggle tiles between blacked-out and visible
function toggleTile(context, n) {
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


function findTile(x, y) {
 
  var i = 0;
  var adjY = y - topOffset;
  var adjX = x - leftOffset;
   console.log("FIND tile top offset = " + topOffset);
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
  //while we've not run out of tiles, aren't right at the bottom
  //of the canvas where there are no tiles and haven't found
  // the right tile yet.
  while (i < tiles.length  && !found) {
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
  if (tileNum != -1){
  console.log("in wordSelectSTART, word: " + tiles[tileNum].word + ' ' + 'word left:'  + tiles[tileNum].left + ' ' + 'tile right:'  + tiles[tileNum].right );
  }
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

      }
      adjustBlackout(context, dragstart, tileNum);
      dragging = false;

    }

  }
 
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