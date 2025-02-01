<div class="home-search-container container-md">
    <div class="input-group home-search-input">
        <i class="bi bi-search"></i>
        <input type="text" class="form-control" id="inlineFormInputGroupUsername2" placeholder="Cerca il tuo sasso...">
    </div>

    <div class="input-group home-select-category">
        <i class="bi bi-columns-gap"></i>
        <select class="form-select" aria-label="Cerca tra tutte le categorie">
            <option class="home-select-category-placeholder" value="" selected>Tutte le categorie</option>
            <?php foreach ($templateParams["categoriesList"] as $category): ?>
                <option value="<?php echo $category["id"]; ?>"><?php echo $category["name"]; ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="home-search-and-filter d-flex gap-2">
        <button class="btn home-search-button">Cerca</button>    
        <button class="btn home-filter-button">
            <i class="bi bi-filter"></i>
        </button>
    </div>
</div>

<section>
	<h2>Categorie più amate</h2>
	<div>
		<div>Categoria 1</div>
		<div>Categoria 2</div>
		<div>Categoria 3</div>
	</div>
</section>

<section>
	<h2>Collezioni più vendute</h2>
	<div>
		<div>Collezione 1</div>
		<div>Collezione 2</div>
		<div>Collezione 3</div>
	</div>
</section>
