<form action="?page=search" method="GET">
    <input type="hidden" name="page" value="search">
    <div class="home-search-container">
        <div class="input-group home-search-input">
            <i class="bi bi-search"></i>
            <input type="text" class="form-control" id="inlineFormInputGroupUsername2" name="filtering" placeholder="Cerca il tuo sasso...">
        </div>

        <div class="input-group home-select-category">
            <i class="bi bi-columns-gap"></i>
            <select class="form-select" aria-label="Cerca tra tutte le categorie" name="category">
                <option class="home-select-category-placeholder" value="-1" selected>Tutte le categorie</option>
                <?php foreach ($dbh->getCategories() as $category): ?>
                    <option value="<?php echo $category["id"]; ?>"><?php echo $category["name"]; ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="home-search-and-filter d-flex gap-2">
            <button type="submit" class="btn home-search-button">Cerca</button>
        </div>
    </div>
</form>

<div class="home-cards-container px-3 px-md-5">
    <section class="section mt-5 home-section">
        <h2 class="text-center home-loved-category-title">Categorie più Amate</h2>
        <div class="row justify-content-center">
            <?php foreach ($templateParams["mostLovedCategories"] as $collection): ?>
                <div class="col-sm-6 col-md-4 col-xl-4">
                    <div class="card h-100 home-loved-category">
                        <img src="<?php echo $collection['image']; ?>" class="card-img-top" alt="<?php echo $collection['alt']; ?>">
                        <div class="card-body home-card-body">
                            <h5 class="card-title"><?php echo $collection['title']; ?></h5>
                            <p class="card-text"><?php echo $collection['description']; ?></p>
                            <a href="<?php echo $collection['href']; ?>" class="btn home-go-to-loved-categories">Scopri la<br/>categoria!</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
    <section class="section mt-5 home-section">
        <h2 class="text-center home-sold-collections-title">Collezioni più Amate</h2>
        <div class="row justify-content-center">
            <?php foreach ($templateParams["mostSoldCollections"] as $category): ?>
                <div class="col-sm-6 col-md-4 col-xl-4">
                    <div class="card h-100 home-sold-collections">
                        <img src="<?php echo $category['image']; ?>" class="card-img-top" alt="<?php echo $category['alt']; ?>">
                        <div class="card-body home-card-body">
                            <h5 class="card-title"><?php echo $category['title']; ?></h5>
                            <p class="card-text"><?php echo $category['description']; ?></p>
                            <a href="<?php echo $category['href']; ?>" class="btn home-go-to-sold-collections">Scopri la<br/>collezione!</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
</div>