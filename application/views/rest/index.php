<html lang="en">
    <head>
        <link rel="stylesheet" href="css/bootstrap.css">
    </head>
    <body>
    <div class="container">
        <h1> Mercados </h1>
        <table class="table">
            <?php foreach($mercados as $mercado) : ?>
            <tr>
                <td><?=$mercado["MER_cnpj"] ?></td>
                <td><?=$mercado["MER_razao_social"] ?></td>
            </tr>
        <?php endforeach ?>
        </table>
        <div>
    </body>
</html>