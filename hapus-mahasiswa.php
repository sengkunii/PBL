<?php require_once('includes/init.php'); ?>
<?php cek_login($role = array(1, 2)); ?>

<?php
$ada_error = false;
$result = '';

$id_mahasiswa = (isset($_GET['id'])) ? trim($_GET['id']) : '';

if(!$id_mahasiswa) {
	$ada_error = 'Maaf, data tidak dapat diproses.';
} else {
	$query = $pdo->prepare('SELECT id_mahasiswa FROM mahasiswa WHERE id_mahasiswa = :id_mahasiswa');
	$query->execute(array('id_mahasiswa' => $id_mahasiswa));
	$result = $query->fetch();
	
	if(empty($result)) {
		$ada_error = 'Maaf, data tidak dapat diproses.';
	} else {
		
		$handle = $pdo->prepare('DELETE FROM nilai_mahasiswa WHERE id_mahasiswa = :id_mahasiswa');				
		$handle->execute(array(
			'id_mahasiswa' => $result['id_mahasiswa']
		));
		$handle = $pdo->prepare('DELETE FROM mahasiswa WHERE id_mahasiswa = :id_mahasiswa');				
		$handle->execute(array(
			'id_mahasiswa' => $result['id_mahasiswa']
		));
		redirect_to('list-mahasiswa.php?status=sukses-hapus');
		
	}
}
?>

<?php
$judul_page = 'Hapus mahasiswa';
require_once('template-parts/header.php');
?>

	<div class="main-content-row">
	<div class="container clearfix">
	
		<?php include_once('template-parts/sidebar-mahasiswa.php'); ?>
	
		<div class="main-content the-content">
			<h1><?php echo $judul_page; ?></h1>
			
			<?php if($ada_error): ?>
			
				<?php echo '<p>'.$ada_error.'</p>'; ?>	
			
			<?php endif; ?>
			
		</div>
	
	</div><!-- .container -->
	</div><!-- .main-content-row -->


<?php
require_once('template-parts/footer.php');