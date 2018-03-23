<?php

namespace App\Http\Controllers;

use App\PropertyImage;
use Illuminate\Http\Request;
use App\Jobs\ImportLVPropertyImages;
use Illuminate\Support\Facades\Bus;


class LvimagesController extends Controller
{
	
    public function index() {

    		$rets_login_url = "http://rets.las.mlsmatrix.com/rets/login.ashx";
            $rets_username = "neal";
            $rets_password = "glvar";
            $rets = new \phRETS();
            $rets->AddHeader("RETS-Version", "RETS/1.7.2");
            $connect = $rets->Connect($rets_login_url, $rets_username, $rets_password);
            if ($connect) {
            	
            	$search = $rets->SearchQuery("Property", "Listing", '(City=LASVEGAS)', array("StandardNames" => 0, 'Limit' => 10, 'Offset' => 1));
            	$result_count = $rets->TotalRecordsFound();
            	
            	for ($i=0; $i < $result_count; $i = $i+4000) { 
            		 $job = (new ImportLVPropertyImages(4000,$i));
                                Bus::dispatch($job);
            	}


            	$search_result = array();
                $key = 0;
                $rowCount = 0;
            	 while ($listing = $rets->FetchRow($search)) {

            	 	$rowCount++;

            	 	$photos = $rets->GetObject("Property", "LargePhoto", $listing['Matrix_Unique_ID'], "*", 0);

            	 	foreach ($photos as $key => $photo) {
            	 		PropertyImage::where('ContentId', '=', $photo['Content-ID'])->delete();
            	 	} //endforeach

            	 	foreach ($photos as $key => $photo) {

					//echo '<img src="data:image/gif;base64,'.base64_encode($photo['Data']).'"/>';
            	 	
            	 	
    	 			if (isset($photo['Content-ID']) && $photo['Content-ID'] != '') {
                        $content_id = $photo['Content-ID'];
   
                    }
                    if (isset($photo['Object-ID']) && $photo['Object-ID'] != '') {
                        $object_id = $photo['Object-ID'];
                    }
                    if (isset($photo['Success']) && $photo['Success'] != '') {
                        $Success = $photo['Success'];
                    }
                    if ($photo['Success'] == true && isset($photo['Content-Type']) && $photo['Content-Type'] != '') {
                        $contentType = $photo['Content-Type'];
                        $property_image = base64_encode($photo['Data']);
                        $search_result[$key]['contentType'] = $photo['Content-Type'];
                        $search_result[$key]['property_image'] = $photo['Data'];
                    } else {
                        $search_result[$key]['contentType'] = '';
                        $search_result[$key]['property_image'] = '';
                    }

                    if (isset($listing['Content-Description']) && $listing['Content-Description'] != '') {
                        $ContentDescription = $listing['Content-Description'];
                    } else {
                        $ContentDescription = '';
                    }

            	 	

                    $propertyimage = new PropertyImage();
                    $propertyimage->Matrix_Unique_ID = $listing['Matrix_Unique_ID'];
                    $propertyimage->MLSNumber = $listing['MLSNumber'];
                    $propertyimage->ContentId = $photo['Content-ID'];
                    $propertyimage->ObjectId = $photo['Object-ID'];
                    $propertyimage->Success = $photo['Success'];
                    $propertyimage->ContentType = $photo['Content-Type'];
                    $propertyimage->Encoded_image = base64_encode($photo['Data']);
                    $propertyimage->ContentDesc = $ContentDescription;
                    $propertyimage->save();


					} //endforeach

            	 } //endwhile
            } //endif 
            
            $rets->FreeResult($search_query);
    }
}
