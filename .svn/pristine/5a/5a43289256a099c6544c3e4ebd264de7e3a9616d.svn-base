<?php
include 'panels/viewart.php';
$articles = getallart();
$display = "<td colspan='100%'>No Article Types Created</td>";
if(isset($articles) && count($articles)>0)
{
    $display = '';
    foreach ($articles as $article)
    {
        $display .= '<tr>';
       
        $display .= '<td>'.$article->artname.'</td>';
        $display .= '<td>'.$article->mintemp.'<sup>0</sup> C To '.$article->maxtemp.' <sup>0</sup> C</td>';
		 $display .= '<td><a href="article.php?id=4&aid='.$article->artid.'">
		 <i class="icon-pencil"></i>
                </td></td>';
        $display .= '<td><a href="route.php?delartid='.$article->artid.'">
		<i class="icon-trash"></i>
               </a></td>';    
    }
}
echo $display;
?>
</tbody>
</table>