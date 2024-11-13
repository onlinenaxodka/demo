		</div>
	</section>
	<script src="/assets/js/jquery-2.2.4.min.js"></script>
	<!-- <script src="/assets/js/tether.min.js"></script> -->
	<script src="/assets/js/popper.min.js"></script>
	<script src="/assets/js/bootstrap.min.js"></script>
	<?=$jquerylib?>
	<script src="/assets/js/admin.js?v=20240522"></script>

	<?if(isset($_SESSION['search_user']) and !empty($_SESSION['search_user']) and $link == '/admin/users.php'):?>
	<script type="text/javascript">dataUser(<?=$search_user_value?>);</script>
	<?endif;?>

</body>
</html>