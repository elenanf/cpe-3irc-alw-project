<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Maquette Ferme Manager</title>
    <link rel="stylesheet" href="../../Public/style.css">

    <!-- Intégration du JS (Partie 2.1) -->
    <!-- <script src="Public/JS/FermeEngine.js" defer></script> -->
    <!-- <script src="Public/JS/main.js" defer></script> -->
</head>

<body>

<?php if (!empty($_SESSION)) { ?>

    <h1>Ferme Manager</h1>

    <section id="inventory">
        <h2>Inventaire</h2>

        <?php
        foreach ($gameConfigRepository->getProducts() as $name => $product) {
            ?>
            <article id="product-<?php echo $name;?>">
                <h3><span class="icon"><?php echo $product->icon;?></span> <?php echo $product->display ?></h3>
                <div>Stock : <output class="stock">0</output></div>
            </article>
            <?php
        }
        ?>
    </section>

    <hr>

    <section id="buildings">
        <h2>Bâtiments</h2>

        <div>
            <?php
            foreach ($gameConfigRepository->getBuildings() as $name => $building) {
                ?>
                <article id="building-<?php echo $name;?>">
                    <h3><?php echo $building->display ?> <br> (Niv. <output class="level">1</output>)</h3>

                    <button class="harvest"><?php echo $building->action ?> <?php echo $building->production ?></button>

                    <button class="upgrade">
                        Améliorer <br>
                        Coût : <output class="cost">
                            <?php
                            echo "1 " . $gameConfigRepository->getProduct($building->cost)->icon;
                            ?>
                        </output>
                    </button>
                </article>
                <?php
            }
            ?>
        </div>
    </section>

<?php } else { ?>
    <div class="error">Vous n'avez pas accès ! Veuillez vous <a href="/login">authentifier</a></div>
<?php } ?>

</body>

</html>


