<?php
//$mysqli = new mysqli("localhost", "root", "", "osetianews");

$mainPageXpath = getXpath('https://iryston.tv/category/news/');
foreach ($mainPageXpath->query("//div[contains(@class, 'first_news_block')]//ul[contains(@class, 'cbp_tmtimeline')]//a") as $item) {
  $title = $mainPageXpath->query(".//time[contains(@class, 'cbp_tmtime')]//h4", $item);
  $date = $mainPageXpath->query(".//time[contains(@class, 'cbp_tmtime')]//div[contains(@class, 'date')]", $item)[0]->textContent;
  $dateText = date("Y-m-d H:i:s ", strtotime($date));

  $image = $mainPageXpath->query(".//div[contains(@class, 'photo-content')]", $item);
  $image_url = $image[0]->getAttribute('style');
  if($image[0] !== null) {
    $image_url = getUrl($image_url);
  }

  $newsUrl = $item->getAttribute('href');

  $newsText = "";
  $articleXpath = getXpath($newsUrl);
  foreach($articleXpath->query("//div[contains(@class, 'news_full_text')]//p") as $text) {
    $newsText .= $text->textContent . "\n";
  }

  // echo "<h3>{$title[0]->textContent}</h3>";
  // echo "<div><img src=\"{$image_url}\"></div>";
  // echo $dateText."\n";
  // echo "<p>{$newsText}</p>";

  // $mysqli->query("INSERT INTO `news`
  // (`website_id`,`title`,`date`,`text`,`img`,`url`)
  // VALUES (5, '{$title}', '{$dateText}', '{$newsText}', '{$image_url}','{$newsUrl}')");
}

function getXpath($url) {
  $html = file_get_contents($url);
  $doc = new DOMDocument();
  @$doc->loadHTML($html);
  $xpath = new DOMXpath($doc);
  return $xpath;
}

function getUrl($str_url) {
  $url = "";
  $ind = 0;
  for ($i = 0; $i < strlen($str_url); $i++) {
    if($str_url[$i - 1] == '(')
    {
      $ind = $i;
      break;
    }
  }
  for ($i = $ind; $i < strlen($str_url); $i++) {
    if($str_url[$i] == ')') break;
    $url .= $str_url[$i];
  }
  return $url;
}
