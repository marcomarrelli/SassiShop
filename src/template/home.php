<section>
	<input type="text" placeholder="Cerca prodotto..." />
	<select>
		<option value="">Tutte le categorie</option>	
		<?php foreach ($templateParams["categoriesList"] as $category): ?>
			<option value="<?php echo $category["id"]; ?>"> <?php echo $category["name"]; ?> </option> 
		<?php endforeach; ?>
	</select>
	<button>Cerca</button>
	<button>Filtri</button>
</section>

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
