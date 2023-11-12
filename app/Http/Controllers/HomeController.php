<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Google\Cloud\Core\ServiceBuilder;

class HomeController extends Controller
{
    public function index()
    {
        $cloud = new ServiceBuilder([ 
            'keyFilePath' => public_path('facial-detection-app.json'), 
        ]);
        
        $vision = $cloud->vision();

        $output = imagecreatefromjpeg(public_path('friend2.jpg'));
        $image = $vision->image(file_get_contents(public_path('friend2.jpg')), ['FACE_DETECTION']);

        $results = $vision->annotate($image);

        foreach ($results->faces() as $face) {
            $vertices = $face->boundingPoly()['vertices'];
            $x1 = $vertices[0]['x'];
            $y1 = $vertices[0]['y'];
            $x2 = $vertices[2]['x'];
            $y2 = $vertices[2]['y'];
            imagerectangle($output, $x1, $y1, $x2, $y2, 0x00ff00);
        }
        
        header('Content-Type: image/jpeg');
        imagejpeg($output); 
        imagedestroy($output);

    }
}
