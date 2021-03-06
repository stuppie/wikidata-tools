<?PHP

// Copyright 2016 Arthur P. Smith
// Licensed under the Apache License, Version 2.0 (the "License");
// you may not use this file except in compliance with the License.
// You may obtain a copy of the License at
// 
//     http://www.apache.org/licenses/LICENSE-2.0
//
// Unless required by applicable law or agreed to in writing, software
// distributed under the License is distributed on an "AS IS" BASIS,
// WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
// See the License for the specific language governing permissions and
// limitations under the License.

// See https://www.wikidata.org/wiki/MediaWiki:Gadget-AuthorityControl.js
// for a number of the conversions done here.

$property = isset($_REQUEST['p']) ? $_REQUEST['p'] : '' ;
$url_prefix = isset($_REQUEST['url_prefix']) ? $_REQUEST['url_prefix'] : '' ;
$url_suffix = isset($_REQUEST['url_suffix']) ? $_REQUEST['url_suffix'] : '' ;
$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '' ;

if (! empty($id) ) {
 switch($property) {
  case 213: // ISNI
    $link_string = str_replace(" ", "", $id) ;
    break ;
  case 345: // IMDB
    switch(substr($id, 0, 2)) {
      case 'nm':
        $link_string = "name/$id/";
        break;
      case 'tt':
        $link_string = "title/$id/";
        break;
      case 'ch':
        $link_string = "character/$id/";
        break;
      case 'ev':
        $link_string = "event/$id";
        break;
      case 'co':
        $link_string = "company/$id/";
        break;
      default:
        $link_string = "/$id" ;
        break;
    }
    break;
  case 502: // HURDAT
    $year = substr($id, 4, 4);
    if (intval($year) < 2005) {
        $link_string = $year . '/'; # Can only link to list for year
    } else {
        $id_in_year= strtolower(substr($id, 0, 4));
        $link_string = "$year/$id_in_year/";
    }
    break;
  case 628: // E number
  case 1323: // TA98
    $link_string = substr($id, 1); // Skip initial letter
    break;
  case 679: // ZVG number needs to be padded to 6 digits (leading 0's)
    $link_string = sprintf('%06d', $id);
    break;
  case 882: // FIPS
    $link_string = substr($id, 0, 2) . "/$id";
    break;
  case 919: // SOC code
    $link_string = str_replace("-", "", $id) ;
    break;
  case 2698: // CricketArchive
    if (is_numeric($id)) {
      if (strlen($id) > 3){
        $trunc = substr($id, 0, -3) ;
      } else {
        $trunc = "0";
      }
      $link_string = "$trunc/$id/$id";
    } else {
      $link_string = $id;
    }
    break;
  default:
    $link_string = $id ;
    break ;
 }

 $redirect_url = $url_prefix . $link_string . $url_suffix ;

 header("Location: $redirect_url");
 exit();
}

print "<html><head><title>Wikidata External ID redirector</title></head>" ;
print "<body><h1>Wikidata External ID redirector</h1>" ;
print "This accepts the following '?' parameters and returns a redirect constructed from them:" ;
print "<ul>" ;
print "<li> p - property number (eg. 213 = ISNI)</li>";
print "<li> url_prefix - eg. http://isni.org/</li>";
print "<li> url_suffix - eg. .html</li>";
print "<li> id - the id value of this external id property for an entity of interest</li>";
print "</ul>";

print "<p>An example: <a href=\"http://tools.wmflabs.org/wikidata-externalid-url/?p=213&url_prefix=http://isni.org/&id=0000 0000 8045 6315\">http://tools.wmflabs.org/wikidata-externalid-url/?p=213&url_prefix=http://isni.org/&id=0000 0000 8045 6315</a>.</p>";

print "<p>Currently supported id translations:</p>";
print "<ul>";
print "<li>ISNI - property 213</li>";
print "<li>IMDB - property 345</li>";
print "<li>HURDAT - property 502</li>";
print "<li>E number - property 628</li>";
print "<li>SOC code - property 919</li>";
print "<li>TA98 - property 1323</li>";
print "<li>CricketArchive - property 2698</li>";
print "</ul>";

print "The <a href=\"https://github.com/arthurpsmith/wikidata-tools/tree/master/wikidata-externalid-url\">source code for this service</a> is available under the <a href=\"http://www.apache.org/licenses/LICENSE-2.0\">Apache License, Version 2.0</a>." ;

print "</body></html>";

?>
