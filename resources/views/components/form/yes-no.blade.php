@props(['name', 'label', 'value' => null, 'required' => true, 'model' => null])

<x-form.radios :name="$name" :label="$label" :options="[1 => 'Yes', 0 => 'No']" :value="$value" :required="$required" :model="$model"
    {{ $attributes }} />
