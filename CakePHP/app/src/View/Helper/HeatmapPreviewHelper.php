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

        $html .= $this->_title($content);
        $html .= $this->_View->element($elementPath, compact('content'));
        
        return $html . '</div>';
    }

    private function _title(Heatmap $content)
    {
        $html = '<div class="content-title">' . $content->name;

        return $html . '</div>';
    }
}
