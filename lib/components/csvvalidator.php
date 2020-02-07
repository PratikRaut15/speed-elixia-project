<?php
function columncount( $filename )
{
 $fieldcount=0;
 $handle = fopen($filename,"r");
    try
    {
        $firstline = fgets($handle);
        $fieldcount = count( split(",", $firstline));
    }
    catch(Exception $e)
    {
        // Error reading the file.
    }
    fclose($handle);

    return $fieldcount;

}

function columnexists( $filename, $columnname)
{
    $columnindex = -1;

    $fileisok = false;
    $fieldmap="";
    // Get the first line from the
    $handle = fopen($filename,"r");
    try
    {
        $firstline = fgets($handle);
        $firstline = str_replace("\"", "", $firstline);
        $fields = split(",", $firstline);

        $fieldindex =0;

        foreach($fields as $thisfield) // Test the field against each base field.
        {
            $found=false;
            $basefieldoptions = split(":", $columnname);
            // Each base field can have several optional versions of the field (including the base)
            foreach($basefieldoptions as $thisoption)
            {
                if( trim( strtoupper( $thisfield)) == trim( strtoupper( $thisoption )))
                {

                    $columnindex = $fieldindex;
                    $found=true;
                    break;
                }
            }
            if($found)
            {
                break;
            }
            $fieldindex++;
        }

    }
    catch(Exception $e)
    {
        // Error reading the file.
    }
    fclose($handle);

    return $columnindex;
}

function validatefile( $filename, $requiredfields, &$fieldmap )
{
    // Validate that the required fields exist in the CSV file.
    // Requires that the 1st row contains the headers.

    $fileisok = false;
    $fieldmap="";
    // Get the first line from the
    $handle = fopen($filename,"r");
    try
    {
        $firstline = fgets($handle);
        // remove any quotes.
        $firstline = str_replace("\"", "", $firstline);
        $fields = split(",", $firstline);

        $basefields = split(",", $requiredfields);

        $requiredfieldcount = count($basefields);
        $matchingfields =0;
        $fieldindex =0;


        foreach( $basefields as $thisbase )
        {
            $fieldindex =0;
            foreach($fields as $thisfield) // Test the field against each base field.
            {
                $found=false;
                $basefieldoptions = split(":", $thisbase);
                // Each base field can have several optional versions of the field (including the base)
                foreach($basefieldoptions as $thisoption)
                {
                    if( trim( strtoupper( $thisfield)) == trim( strtoupper( $thisoption )))
                    {
                        $matchingfields++;
                        $fieldmap .= ($fieldmap!=""?",":"") . $fieldindex;
                        $found=true;
                        break;
                    }
                }
                if($found)
                {
                    break;
                }
                $fieldindex++;
            }

        }
        if($matchingfields >= $requiredfieldcount )
        {
            $fileisok=true;
        }
    }
    catch(Exception $e)
    {
        // Error reading the file.
    }
    fclose($handle);

    return $fileisok;

}
?>
