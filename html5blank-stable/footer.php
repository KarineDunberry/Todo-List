			<!-- footer -->
			<footer class="footer" role="contentinfo">

				<!-- copyright -->
				<p class="copyright">
					&copy; <?php echo date('Y'); ?> Copyright <?php bloginfo('name'); ?>. <?php _e('Powered by', 'html5blank'); ?>
					<a href="//wordpress.org" title="WordPress">WordPress</a> &amp; <a href="//html5blank.com" title="HTML5 Blank">HTML5 Blank</a>.
				</p>
				<!-- /copyright -->

			</footer>
			<!-- /footer -->

		</div>
		<!-- /wrapper -->

		<?php wp_footer(); ?>

		<script src="https://code.jquery.com/jquery-3.5.1.min.js" crossorigin="anonymous"></script>		
		<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
		<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>

		<script type="text/javascript">
//input qui disparait doucement, faire on animationend, et là enlever le input
			function addTodo() {
				let nouvelleTache = $('input[name=nouvelleTache]').val();  //pour un input on doit aller chercher la value et non le text

				if (nouvelleTache == "") {
					toastMessage("Attention!", "Donnez un nom à votre nouvelle tâche.");
				} else {
					$.ajax({
						url: "/?rest_route=/html5blank-stable/todo",
						data: {'titre': nouvelleTache},
						method: 'POST',
						error: showErrorAjax,
						success: showTodo
					});
				}
			}

			function alertMessage(message, type) {
				let alertClone = $('#template-alert').clone();
				alertClone.attr('id', "");

				alertClone.find('.titleAlert').text(message);
				let typeAlert = "alert-info";

				if (type != undefined) {
					typeAlert = type;
				}

				alertClone.addClass(typeAlert);
				alertClone.show();
				$('#showAlert').append(alertClone);  //toujours mettre un append (div vide pour recevoir le tout)
			}

			function check() {
				let checkbox = $(this).parent('.flex-grow-1').parent('.tache');
				let idTodo = $(this).parent('.flex-grow-1').parent('.tache').find('.btn-supprimerTache').attr('post-id');
				let texteTache = $(this).siblings('.texteTache').text();
				let done = false;

				if ($(this).prop("checked") == true) {
					$(checkbox).css('background-color', '#ececf3');
					done = true;
				} else if ($(this).prop("checked") == false) {
					$(checkbox).css('background-color', '#ffffff');
				}	
				console.log(done);

				$.ajax({ 
					url: "/?rest_route=/html5blank-stable/todo",
					data: {
						'id' : idTodo,
						'titre': texteTache,
						'done': done
					},
					method: 'POST',
					error: showErrorAjax, 
					success: checkSuccess
				});
			};

			function checkSuccess(data, status) {
				if (status == 'success') {
					let idTodo = $('#todo-' + data.id);

					if (data.done) {
						idTodo.find('.checkbox').prop('checked', true);
					} 
				}
			}

			function createListElement(textTodo, idTodo, doneTodo) {
				if (idTodo != undefined) {
					let cloneTache = $('#template-tache').clone();
					let btnSupprimer = cloneTache.find('.btn-supprimerTache');
					let li = cloneTache.find('.tache');

					if (doneTodo) {
						cloneTache.find('.checkbox').prop('checked', true);
						li.css('background-color', '#ececf3')
					}

					cloneTache.find('.texteTache').text(textTodo);
					btnSupprimer.attr('post-id', idTodo);
					li.attr('id', 'todo-' + idTodo);
					$('.list-group').append(cloneTache);
					cloneTache.show();
							
					$('input[name=nouvelleTache]').val(''); //clear sur input
					$('.btn-enregistrerTache').prop('disabled', true);

					$('.btn-enregistrerTache:last').click(enregistrer);
					$('.btn-modifierTache:last').click(modifier);
					$('.btn-supprimerTache:last').click(supprimer);
					$('.checkbox:last').change(check);
					$('.flex-grow-1:last').click(select);
				} else {
					alertMessage('Problème au niveau du serveur', 'alert-danger');
				}
			}

			function enregistrer() {
				let texteTacheModif = $(this).siblings('.flex-grow-1').find('.modifierTache').val();
				let idTodo = $(this).siblings('.btn-supprimerTache').attr('post-id');
				let done = $(this).siblings('.flex-grow-1').find('.checkbox').prop("checked") == true;

				if (idTodo != undefined) {
					$.ajax({
						url: "/?rest_route=/html5blank-stable/todo",
						data: {
							'titre': texteTacheModif,
							'id': idTodo,
							'done': done   
						},
						method: 'POST',
						error: showErrorAjax, 
						success: modifySuccess
					});	
				} else {
					alertMessage('Problème au niveau du serveur', 'alert-danger');
				}
			}

			function getTodos(data, status) {
				if (status == 'success') {
					$(data).each(function(position, todo) {
						createListElement(todo.titre, todo.id, todo.done); //ID en majuscule
					});
				} else {
					alertMessage('Problème au niveau du serveur', 'alert-danger');
				}
			}

			function modifier() {
				let texteTacheActuel = $(this).parent('li').find('.texteTache').text();
				let done = $(this).parent('li').find('.checkbox').prop('checked');
				
				
				

				$(this).siblings('.btn-enregistrerTache').prop('disabled', false); 
				$(this).prop('disabled', true);
				$(this).siblings('.btn-supprimerTache').prop('disabled', true);
				$('#btn-nouvelleTache').prop('disabled', true);

				$(this).parent('li').find('.flex-grow-1').html('<input class="checkbox" type="checkbox">'
																+'<input class="modifierTache" type="text" value="' + texteTacheActuel + '"autofocus>');

				$(this).parent('li').find('.checkbox').prop('checked', done);

				let input = $(this).parent('li').find('.modifierTache');
				input.addClass('animate__animated animate__fadeInLeft');
			
			};

			function modifySuccess(data, status) {
				if (status == 'success') {
					console.log(data.id);
					console.log(data.done);
					let idTodo = $('#todo-' + data.id); //id en minuscule puisque l'objet de retour id est en minuscule dans functions.php
					let nouveauTitre = data.titre; 

					idTodo.find('.btn-modifierTache').prop('disabled', false);
					idTodo.find('.btn-supprimerTache').prop('disabled', false);
					idTodo.find('.btn-enregistrerTache').prop('disabled', true);
					$('#btn-nouvelleTache').prop('disabled', false);

					

					let input = idTodo.find('.modifierTache');
					input.addClass('animate__animated animate__fadeOut')
					input.on('animationend', function() {
						idTodo.find('.flex-grow-1').html('<input class="checkbox" type="checkbox">'
														+'<div class="texteTache">' + nouveauTitre + '</div>');

						idTodo.find('.checkbox').prop('checked', data.done);
						
						let textBox = idTodo.find('.texteTache');
						textBox.addClass('animate__animated animate__fadeIn');
					});	
				} else {
					alertMessage('Problème au niveau du serveur', 'alert-danger');
				}
			}

			function select() {
				let li = $(this).parent('li');

				if ($(li).css('background-color') === 'rgb(236, 236, 243)' && $(this).parent('li').find('.checkbox').prop("checked") == false) {   // ne fonctionne pas si pas en rgb
					$(li).css('background-color', '#ffffff');
				} else {
					$(li).css('background-color', '#ececf3');
				}
			}

			function showErrorAjax() {
				console.log('Erreur!');
				alertMessage('Problème au niveau du serveur', 'alert-danger');
			}

			function showTodo(data, status) {
				//pour un input on doit aller chercher la value et non le text
				if (status == 'success') {
					createListElement(data.titre, data.id);
					$('li:last').addClass('animate__animated animate__flash');
				} else {
					alertMessage('Problème au niveau du serveur', 'alert-danger');
				}	
			}

			function successDelete(data, status) {
				if (status == 'success') {
					let idTodo = $('#todo-' + data.ID);
					let deleteValue = $(idTodo).find('.texteTache').text();

					if (deleteValue == "") {
						deleteValue = "L'élément";
					}

					idTodo.addClass('animate__animated animate__fadeOut');
					idTodo.on('animationend', function() {
						idTodo.remove();
						alertMessage(deleteValue + ' a été supprimé');
					});
				} else {
					alertMessage('Problème au niveau du serveur', 'alert-danger');
				}
			}

			function supprimer() {
				let post_id = $(this).attr('post-id');

				if (post_id != undefined) {
					$.ajax({
						url: "/?rest_route=/html5blank-stable/todo&id=" + post_id,
						//serveur doit recevoir id (dans URL puisque qu'on l'obtient par un GET)
						method: 'DELETE',
						error: showErrorAjax,
						success: successDelete
					})
				} else {
					alertMessage('Problème au niveau du serveur', 'alert-danger');
				}		
			};

			function toastMessage(messageTitre, messageTexte) {
				let toastClone = $('#template-toast').clone();
				toastClone.attr('id', "");

				toastClone.find('.titleToast').text(messageTitre);
				toastClone.find('.timeToast').text('1 minute');
				toastClone.find('.textToast').text(messageTexte);
				//toastClone.find('#template-toast').attr('data-delay', '5000'); //incapable de changer l'attribut ici
				$('#showAlert').append(toastClone); 
				toastClone.toast('show');
			}

			$(document).ready(function() {
				$.ajax({
					url: "/?rest_route=/html5blank-stable/todo",
					method: 'GET',
					error: showErrorAjax,
					success: getTodos
				});
				$('#btn-nouvelleTache').click(addTodo);		

			});
		</script>

	</body>
</html>
