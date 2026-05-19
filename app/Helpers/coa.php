<?php

use Illuminate\Support\Collection;

if (!function_exists('renderCoaOptions')) {

    /**
     * COA Tree Renderer (Collection-safe)
     */
    function renderCoaOptions(Collection $accounts, $parentId = null, int $level = 0): string
    {
        $html = '';

        $children = $accounts->get($parentId, collect());

        foreach ($children as $account) {

            $indent = str_repeat('— ', $level);

            $html .= '<option value="' . $account->id . '" data-type="' . $account->type . '">';
            $html .= $indent . $account->code . ' - ' . $account->name;
            $html .= '</option>';

            $html .= renderCoaOptions($accounts, $account->id, $level + 1);
        }

        return $html;
    }
}
