<?php include 'includes/header.php'; ?>

<main>

<style>
.section-title{
    color:#c9a227;
}
</style>

<!-- CAROUSEL -->
<div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel">

    <div class="carousel-indicators">
        <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0"
            class="active"></button>
        <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1"></button>
        <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2"></button>
    </div>

    <div class="carousel-inner">
        <div class="carousel-item active">
            <img src="images/banner1.jpg" class="d-block w-100" alt="Banner 1">
        </div>

        <div class="carousel-item">
            <img src="images/banner2.jpg" class="d-block w-100" alt="Banner 2">
        </div>

        <div class="carousel-item">
            <img src="images/banner3.jpg" class="d-block w-100" alt="Banner 3">
        </div>
    </div>

    <button class="carousel-control-prev" type="button"
        data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
        <span class="carousel-control-prev-icon"></span>
    </button>

    <button class="carousel-control-next" type="button"
        data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
        <span class="carousel-control-next-icon"></span>
    </button>

</div>

<!-- WELCOME SECTION -->
<div class="container mt-5">

    <div class="row">
        <div class="col-md-12 text-center">
            <h1 class="section-title">Welcome To ModestWear Global</h1>
        </div>
    </div>

    <div class="row mt-5">

        <div class="col-md-6">
            <img src="images/wlogo.png" style="width:100%;" height="300" />
        </div>

        <div class="col-md-6">

            <div class="card">
                <div class="card-body">

                    <h5 class="card-title section-title">
                        Cultural Attire Marketplace
                    </h5>

                    <p class="card-text">
                        ModestWear Global is a cultural attire specialized marketplace that connects
                        customers with clothing suppliers offering modest and traditional fashion
                        from around the world. Our platform allows customers to explore a wide
                        variety of cultural outfits such as ethnic dresses, modest wear, traditional
                        garments, and cultural fashion accessories.

                    </p>

                </div>
            </div>

        </div>

    </div>

</div>

</main>

<?php include 'includes/footer.php'; ?>