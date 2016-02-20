<?php

use \Phalcon\Tag;

class AbTag extends Tag
{
    const TagType = 'tagType';

    const AbTag = 'abTag';

    static public function textField($parameters)
    {
        if (self::isAbFormField($parameters)) {
            $view = KxApplication::current()->view;
            $itemViewMode = $view->getVar('itemViewMode');
            return AbTag::formTextField($parameters, $itemViewMode);
        }
        return Tag::textField($parameters);
    }

    static public function select($parameters, $data = NULL)
    {
        if (self::isAbFormField($parameters)) {
            $view = KxApplication::current()->view;
            $itemViewMode = $view->getVar('itemViewMode');
            return AbTag::formSelect($parameters, $itemViewMode);
        }
        $data = $parameters['data'];
        return Tag::select($parameters, $data);
    }

    static public function dateField($parameters)
    {
        if (self::isAbFormField($parameters)) {
            $view = KxApplication::current()->view;
            $itemViewMode = $view->getVar('itemViewMode');
            return AbTag::formDateField($parameters, $itemViewMode);
        }

        return Tag::dateField($parameters);
    }

    static public function tagHtml($tagName, $parameters = NULL, $selfClose = NULL, $onlyStart = NULL, $useEol = NULL)
    {
        if ('province' == $tagName) {
            return self::province($parameters[0], $parameters[1]);
        } else if ('city' == $tagName) {
            return self::city($parameters[0], $parameters[1], $parameters[2]);
        } else if ('county' == $tagName) {
            return self::county($parameters[0], $parameters[1], $parameters[2]);
        } else if ('img_upload' == $tagName) {
            return self::imageUpload($parameters);
        } else if ('file_upload' == $tagName) {
            return self::fileUpload($parameters);
        }
        return Tag::tagHtml($tagName, $parameters, $selfClose, $onlyStart, $useEol);
    }

    /**********************************************************************/
    private static function isAbFormField($p) {
        if (array_key_exists(self::TagType, $p) &&
            $p[self::TagType] == self::AbTag) {
            return true;
        }
        return false;
    }

    private static function emptyHolder(&$p, $a)
    {
        foreach ($a as $i) {
            if (array_key_exists($i, $p) && $p[$i]) {
            } else {
                $p[$i] = '';
            }
        }
    }

    private static function formTextField($p, $itemViewMode) {
        $html = <<<HTML
<div class="Text">
    <label class="control-label">{{label}}</label>

    <div class="controls">
        <input type="text" placeholder="{{placeholder}}" class="m-wrap small" {{validate}} name="{{field}}" value="{{value}}" />
        <span class="help-inline"></span>
    </div>
</div>
HTML;
        self::emptyHolder($p, ['label', 'placeholder', 'field', 'value']);
        $p['validate'] = self::dataRules($p['validate']);

        return Strings::format($html, $p);
    }

    private static function formSelect($p, $itemViewMode) {
        $html1 = <<<HTML
<div class="Select">
    <label class="control-label">{{label}}</label>

    <div class="controls">
        <select type="text" name='{{field}}' class="m-wrap small {{searchable}}">
HTML;

        $html2 = <<<HTML
        </select>
        <span class="help-inline"></span>
    </div>
</div>
HTML;

        $data = $p['data'];
        unset($p['data']);
        $options = '<option value="0">请选择</option>';
        foreach ($data as $i)
        {
            $v = $i;
            if (!is_array($i)) {
                $v = array(
                    'name' => $i, 'value' => $i
                );
            }

            $option = '<option value="{{value}}">{{name}}</option>';
            $options .= Strings::format($option, $v);
        }

        $html = $html1 . $options . $html2;

        self::emptyHolder($p, ['label', 'placeholder', 'field', 'value', 'searchable']);
        if ($p['searchable'] != '') {
            $p['searchable'] = 'chosen';
        }

        return Strings::format($html, $p);
    }

    private static function formDateField($params)
    {
        $html = <<<HTML
<div class="DTP">
    <label class="control-label">{{label}}</label>
    <div class="controls">
        <input class="m-wrap m-ctrl-medium date-picker" readonly
               placeholder="{{placeholder}}" size="16" type="text"
               name="{{field}}" value="{{value}}"/>
    </div>
</div>
HTML;
        self::emptyHolder($params, ['label', 'placeholder', 'field', 'value']);
        return Strings::format($html, $params);
    }

    private static function province($widgetId, $field)
    {
        $html1 = <<<HTML
<div widget-class="RegionSelector" widget-id="{{widget_id}}" mode="province" class="pull-left margin-right-20" style="float: left">
    <select name='{$field}'>
        <option value="-1">请选择省</option>
HTML;
        $options = array();
        foreach (SysRegion::provinces() as $p) {
            $o = "<option value=\"{$p['sys_region_index']}\">{$p['sys_region_name']}</option>";
            array_push($options, $o);
        }
        $options = join('', $options);

        $html2 = <<<HTML
    </select>
</div>
HTML;
        return Strings::format($html1 . $options . $html2, array('widget_id' => $widgetId));

    }

    private static function city($widgetId, $field, $listenTo) {
        $html = <<<HTML
<div widget-class="RegionSelector" widget-id="{$widgetId}" mode="city" class="pull-left margin-right-20" listen-to="{$listenTo}">
    <select name='{$field}'>
    </select>
</div>
HTML;
    return $html;
    }

    private static function county($widgetId, $field, $listenTo) {
        $html = <<<HTML
<div widget-class="RegionSelector" widget-id="{$widgetId}" mode="county" class="pull-left margin-right-20" listen-to="{$listenTo}">
    <select name='{$field}'>
    </select>
</div>
HTML;
        return $html;
    }

    private static function imageUpload($parameters) {
        $html = <<<HTML
<label class="control-label">Image Upload</label>
<div class="controls">
    <div class="fileupload fileupload-new" data-provides="fileupload">
        <div class="fileupload-new thumbnail" style="width: 200px; height: 150px;">
            <img src="media/image/AAAAAA&amp;text=no+image" alt="" />
        </div>
        <div class="fileupload-preview fileupload-exists thumbnail" style="max-width: 200px; max-height: 150px; line-height: 20px;"></div>
        <div>
            <span class="btn btn-file"><span class="fileupload-new">选择文件</span>
            <span class="fileupload-exists">Change</span>
            <input type="file" class="default" /></span>
            <a href="#" class="btn fileupload-exists" data-dismiss="fileupload">移除</a>
        </div>
    </div>
    <!--
    <span class="label label-important">NOTE!</span>
    <span>
    Attached image thumbnail is
    supported in Latest Firefox, Chrome, Opera,
    Safari and Internet Explorer 10 only
    </span>
    -->
</div>
HTML;
        return Strings::format($html, $parameters);
    }

    private static function fileUpload($parameters) {
        $html = <<<HTML
<label class="control-label">Advanced</label>
<div class="controls">
    <div class="fileupload fileupload-new" data-provides="fileupload">
        <div class="input-append">
            <div class="uneditable-input">
                <i class="icon-file fileupload-exists"></i>
                <span class="fileupload-preview"></span>
            </div>
            <span class="btn btn-file">
            <span class="fileupload-new">选择文件</span>
            <span class="fileupload-exists">Change</span>
            <input type="file" class="default" />
            </span>
            <a href="#" class="btn fileupload-exists" data-dismiss="fileupload">移除</a>
        </div>
    </div>
</div>
HTML;
        return Strings::format($html, $parameters);
    }

    private static function dataRules($array)
    {
        $rules = array();
        foreach ($array as $key => $value) {
            //$value = 1? "'true'";
            if (is_numeric($key) && in_array($value, array("required", "email"))) {
                array_push($rules, "data-rule-$value='true'");
                continue;
            }
            array_push($rules, "data-rule-$key='$value'");
        }
        return implode(' ', $rules);
    }
}