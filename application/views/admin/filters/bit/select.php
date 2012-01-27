<div>
	<label for="hash">{label}</label>
	<select name="{name}" id="{hash}">
		<option value="">{default?}</option>
		{options}
			<option value="{option_key}">{option_label}</option>
		{/options}
	</select>
</div>