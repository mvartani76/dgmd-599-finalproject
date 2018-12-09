<?php
namespace App\View\Helper;

use App\Model\Entity\Heatmap;
use Cake\View\Helper;

class HeatmapPreviewHelper extends Helper
{

    /**
     * One new preview function that handles everything.
     */
    public function preview(Heatmap $content, $withRadio = false)
    {
        $layout = strtolower($content->layout);
        $subLayout = strtolower($content->sub_layout);
        $elementPath = "Marketing/Heatmaps/previews/default";
        
        $html = '<div>';

        //if ($withRadio) {
        //    $html .= '<label class="MediaSelectionWrapperLabel" for="campaign-content-id-' . $content->id . '">';
       // }

        $html .= $this->_title($content);
        $html .= $this->_View->element($elementPath, compact('content'));

        //if ($withRadio) {
        //    $html .= $this->_View->element('Marketing/Content/templates/checkbox', compact('content'));
        //    $html .= '</label>';
        //}
        
        pr($html);
        
        return $html . '</div>';
    }

    private function _title(Heatmap $content)
    {
        $html = '<div class="content-title">' . $content->name;

        return $html . '</div>';
    }
}
