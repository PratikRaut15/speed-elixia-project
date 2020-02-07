<?php
class PointLocation {
    var $onvertex = true;

    function PointLocation()
    {}

        function checkPointStatus($point, $polygon, $pointOnVertex = true)
        {

        $this->onvertex = $pointOnVertex;
        $point = $this->TransformToCoordinates($point);
        $point['x'] = abs($point['x']);
        $point['y'] = abs($point['y']);

        $vertices = array();
        foreach ($polygon as $vertex)
        {
            $vertices[] = $this->TransformToCoordinates($vertex);
        }

        // Check if the point lies exactly on a vertex
        if ($this->onvertex == true && $this->pointOnVertex($point, $vertices) == true)
        {
            return "vertex";
        }

        // Check if the point is inside the polygon or on the edge
        $intersections = 0;
        $vertices_count = count($vertices);

        for ($i=1; $i <= $vertices_count; $i++)
        {
            $vertex1 = $vertices[$i-1];
            $vertex2 = $vertices[$i];
            $vertex1['x'] = abs($vertex1['x']);
            $vertex1['y'] = abs($vertex1['y']);
            if($i == $vertices_count)
            {
                $vertex2['x'] = abs($vertices[0]['x']);
                $vertex2['y'] = abs($vertices[0]['y']);
            }
            else
            {
                $vertex2['x'] = abs($vertex2['x']);
                $vertex2['y'] = abs($vertex2['y']);
            }

            // Point lies on horizontal edge
            if ($vertex1['y'] == $vertex2['y'] && $vertex1['y'] == $point['y'] && $point['x'] > min($vertex1['x'], $vertex2['x']) && $point['x'] < max($vertex1['x'], $vertex2['x']))
            {
                return "edge";
            }

            // Point lies on the any edge other than horizontal
            if ($point['y'] > min($vertex1['y'], $vertex2['y']) && $point['y'] <= max($vertex1['y'], $vertex2['y']) && $point['x'] <= max($vertex1['x'], $vertex2['x']) && $vertex1['y'] != $vertex2['y'])
            {
                $xinters = ($point['y'] - $vertex1['y']) * ($vertex2['x'] - $vertex1['x']) / ($vertex2['y'] - $vertex1['y']) + $vertex1['x'];
                if ($xinters == $point['x'])
                {
                    return "edge";
                }
                if ($vertex1['x'] == $vertex2['x'] || $point['x'] <= $xinters)
                {
                    $intersections++;
                }
            }
        }
        // If the number of edges we pass through is odd, then it's in the polygon.
        if ($intersections % 2 != 0)
        {
            return "inside";
        } 
        else
        {
            return "outside";
        }
    }

    function pointOnVertex($point, $vertices)
    {
        foreach($vertices as $vertex)
        {
            if ($point == $vertex)
            {
                return true;
            }
        }

    }

    function TransformToCoordinates($pointString)
    {
        $coordinates = explode(" ", $pointString);
        return array("x" => $coordinates[0], "y" => $coordinates[1]);
    }
}
?>