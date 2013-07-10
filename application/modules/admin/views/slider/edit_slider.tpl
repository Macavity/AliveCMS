<section class="box big">
	<h2>Edit slide</h2>

	<form onSubmit="Slider.save(this, {$slide.id}); return false" id="submit_form">
    <!-- @alive -->
    <input type="hidden" id="jsImagePath" value="{$image_path}" />
    <label for="text">Slider Titel (optional)</label>
    <input type="text" name="title" id="title" value="{$slide.title}"/>

    <label for="text">Slider Text (optional)</label>
    <input type="text" name="text" id="text" value="{$slide.text}"/>

    <label for="image">Bildpfad (innerhalb des theme-Ordners)</label>
    <input type="text" name="image" id="image" placeholder="http://" value="{preg_replace('/{path}/', '', $slide.image)}" class="jsSliderUrl"/>
    <img src="{$image_path}{preg_replace('/{path}/', '', $slide.image)}" class="jsSliderPreview"/>

		<label for="link">Link (optional)</label>
		<input type="text" name="link" id="link" placeholder="http://"value="{$slide.link}"/>

		<input type="submit" value="Save slide" />
	</form>
</section>