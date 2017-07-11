<?php
//function display_page($page_num, $current_text){
 // echo "in display_page";
  $current_text = $_GET['current_text'];
  // $page_num = $_GET['page_num'];
  // echo "current_text " . $current_text;
  // echo "page num: " . $page_num;
  //get the right file path  

    // Non Fiction
  if ($current_text == "dream") {
    $text_file = "./Texts/NonFiction/DreamPsychology.txt";
  }
  elseif ($current_text == "music"){
    $text_file = "./Texts/NonFiction/ShakespeareandMusic.txt";
  }
  elseif ($current_text == "tides"){
     $text_file = "./Texts/NonFiction/TimeandTide.txt";
  }
  
  elseif ($current_text == "unix"){
     $text_file = "./Texts/NonFiction/ArtofUnix.txt";
  }
  elseif ($current_text == "alchemy"){
     $text_file = "./Texts/NonFiction/StoryofAlchemy.txt";
  }
  elseif ($current_text == "airplane"){
     $text_file = "./Texts/NonFiction/HistoryOfAirplane.txt";
  }

  // Fiction

  elseif ($current_text == "frank"){
     $text_file = "./Texts/Fiction/Frankenstein.txt";
  }
  elseif ($current_text == "super"){
     $text_file = "./Texts/Fiction/AstoundingStories.txt";
  }
elseif ($current_text == "alice"){
     $text_file = "./Texts/Fiction/AliceInWonderland.txt";
  }
  elseif ($current_text == "dracula"){
     $text_file = "./Texts/Fiction/Dracula.txt";
  }
elseif ($current_text == "moby"){
     $text_file = "./Texts/Fiction/MobyDick.txt";
  }
  elseif ($current_text == "pride"){
     $text_file = "./Texts/Fiction/PrideAndPrejudice.txt";
  }
  elseif ($current_text == "sherlock"){
     $text_file = "./Texts/Fiction/SherlockHolmes.txt";
  }


  // Politics
elseif ($current_text == "cons17"){
     $text_file = "./Texts/Politics/ConservativeManifesto2017.txt";
  }
  elseif ($current_text == "lab17"){
     $text_file = "./Texts/Politics/LabourManifesto2017.txt";
  }
elseif ($current_text == "lib17"){
     $text_file = "./Texts/Politics/LibDemManifesto2017.txt";
  }
  elseif ($current_text == "snp17"){
     $text_file = "./Texts/Politics/SNPManifesto2017.txt";
  }
elseif ($current_text == "trumpcong"){
     $text_file = "./Texts/Politics/TrumpCongressTranscript.txt";
  }
  elseif ($current_text == "trumpin"){
     $text_file = "./Texts/Politics/TrumpInauguration2016.txt";
  }
  elseif ($current_text == "trumpcpac"){
     $text_file = "./Texts/Politics/TrumpTranscriptCPACSpeech.txt";
  }
elseif ($current_text == "trumphealth"){
     $text_file = "./Texts/Politics/TrumpTranscriptRepublicanHealthBill.txt";
  }
  else{

    $text_file = "./Texts/TreesandShrubs.txt";
  }
   // echo "<br>";
   // echo $text_file;



  $filestring = file_get_contents($text_file) or die("Unable to open file!");
  // echo "<p>";
  echo $filestring;
  // echo "</p>";
//   $words = str_replace("\n",' ',  $filestring);
//   $words = explode(" ", $words);
//   $numwords = count($words);
//   $num_pages = intdiv($numwords,$page_length);
// // if page_num = -100 select a random page in the range of 
// //possible pages for the current text. Not currently using this

//   if ($page_num == -100) {
//    $page_num = rand(1,$num_pages);
//   }


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
//      $current_page++;
//      echo "</p>";
//      echo "</div>";
//     }
//   else {
//     echo "<br><br>";
//     echo "<br><br>";
//     echo "<div>";
//     echo "<p>";
//     echo "Can't display that page!";
//     echo "</p>";
//     echo "</div>";
//   }
?>