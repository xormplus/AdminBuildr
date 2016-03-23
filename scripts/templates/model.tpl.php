<?php

class {{ model_name }} extends {{ base_model_name }}
{
	public function initialize()
	{
		$this->allowEmptyStringValues(array({% for field in allow_empty_fields %}'{{field}}', {% end %}));
	}

	public function getSource() {
		return "{{ table_name }}";
	}

	public static function primaryKeyName() {
		return "{{ primary_key }}";
	}

	public static function search($search, $order=false)
	{
		return parent::search($search);
	}

	public static function headers() {
		return array({% for field in fields_info %}
			'{{field['fieldName']}}' => '{{field['fieldText']}}',{% end %}
		);
	}

	public static function fieldForDelete() {
		return array('field' => "{{ field_for_delete}}", 'value' => '{{ value_for_delete }}');
	}

	public static function isLikeField($field) {
		return in_array($field, array({% for field in like_fields %}'{{field}}', {% end %}));
	}
}