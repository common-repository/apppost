<?php

/**
 * Plugin Name: AppPost
 * Plugin URI: http://codestripdev.com/
 * Description: Allows users to grab some simple infomation about an Apple Store App using a short code within a post.
 * Version: 1.0
 * Author: Jackson Isted - CodeStrip Dev Team
 * Author URI: http://codestripdev.com/
 * License: PL2
 */

/*  Copyright 2013 Jackson Isted (email : hawks008@codestripdev.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

    function searchStore ($id, $searchCountry){
    	$applicationjson = file_get_contents('http://itunes.apple.com/lookup?id=' . $id . '&entity=software&country='.$searchCountry);
    	return json_decode($applicationjson);
	}
    
    function getAppFromStore($id, $searchCountry) {
        $searchResult = searchStore($id, $searchCountry);
        $numberOfResults = count($searchResult->results);
        if ($numberOfResults > 0) {
            return $searchResult->results[0];
        } else {
            return null;
        }
                
    }
	
	function getAppBasicsHtml($atts, $content = null){
	    extract( shortcode_atts( array('appid' => '','searchcountry' => 'AU',), $atts ) );
	    //$app = getAppFromStore($atts[0],$atts[1]); //TESTS
	    $app = getAppFromStore($appid,$searchcountry);

        
        
        $finishedhtml = "";
        if ($app <> null) {
            $name = $app->trackCensoredName;
            $icon = $app->artworkUrl60;
            $dev = $app->artistName;
            $price = $app->formattedPrice;
            $currency = "";
            //testing if free so as to not display currency
            if($price <> "Free") {$price = $app->formattedPrice." ".$app->currency;}
            
            $version = $app->version;
            $url = $app->trackViewUrl;
            ?>
            <div style="text-align: center">
                <div style="display: inline-block; max-width: 600px !IMPORTANT">
                    <table border="0">
                        <tr>
                            <td rowspan="3">
                                <div style="text-align: center">
                                    <div style="background: url('<?php echo $icon; ?> '); width: 57px; height: 57px; display: inline-block; -webkit-border-radius: 10px; -moz-border-radius: 10px; border-radius: 10px;"></div>
                                    </div>
                            </td>
                            <td>Name: <?php echo $name; ?></td>
                        </tr>
                        <tr>
                            <td>Developer: <?php echo $dev; ?></td>
                        </tr>
                        <tr>
                            <td>Price: <?php echo $price; ?></td>
                        </tr>
                        <tr>
                            <td>
                                <a href="<?php echo $url; ?>&at=11l9Ln">
                                    <div style="background: url('http://linkmaker.itunes.apple.com/htmlResources/assets//images/web/linkmaker/badge_appstore-sm.svg'); width: 60px; height: 15px;"></div>
                                </a>
                            </td>
                            <td>Version: <?php echo $version; ?></td>
                        </tr>
                    </table>
                </div>
            </div>
<?php        }
	    return;
	}
    //$testsearch[0] = 697846300;
    //$testsearch[1] = "AU";
    //echo getAppBasicsHtml($testsearch);

    add_shortcode( 'appbasics', 'getAppBasicsHtml' );
?>