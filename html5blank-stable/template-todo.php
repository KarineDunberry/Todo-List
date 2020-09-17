<?php /* Template Name: Ma ToDo List */ get_header(); ?>
        
        <!--TEMPLATES-->
        <div id="template-alert" class="alert alert-dismissible fade show" role="alert">
            <strong class="titleAlert"></strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
       
        <div id="template-toast" class="toast" role="alert" aria-live="assertive" aria-atomic="true" data-delay="3000"> <!--autohide par défaut à true-->
            <div class="toast-header">
                <strong class="mr-auto titleToast"></strong>
                <small class="timeToast"></small>
                <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="toast-body textToast"></div>
        </div>

        <div id="template-tache" style="display:none;">
            <li class="tache d-flex list-group-item">
                <div class="flex-grow-1">
                    <input class="checkbox" type="checkbox">
                    <div class="texteTache"></div>
                </div>
                <button type="submit" class="btn-modifierTache"><i class="fas fa-pencil-alt"></i></button>
                <button type="submit" class="btn-enregistrerTache"><i class="far fa-save"></i></button>
                <button type="submit" class="btn-supprimerTache"><i class="fas fa-trash-alt"></i></button>
            </li>
        </div>
      

        <!--HTML-->

		<div class="container">
            <div class="row">
                <h1 class="col-12 text-center">Ma ToDo List</h1>
                <div class="imageBienvenue">
                    <div class="col-12 d-flex justify-content-center nouvelleTache">
                        <input type="text" id="inputNouvelleTache" name="nouvelleTache" placeholder="Entrer une nouvelle tâche" autofocus>
                        <button type="submit" id="btn-nouvelleTache" name="btn-nouvelleTache" value="btn-nouvelleTache">Ajouter</button>
                    </div>
                </div>
            </div>
            <h2>Aujourd'hui</h2>
            <div class="row">
                <div class="col-12">
                    <div id="showAlert"></div>
                    <ul class="list-group">
                        <!--tâches-->    
                    </ul>
                </div>
            </div>
        </div>



<?php get_footer(); ?>
