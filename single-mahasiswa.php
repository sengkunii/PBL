<?php require_once('includes/init.php'); ?>

<?php
$ada_error = false;
$result = '';

$id_mahasiswa = (isset($_GET['id'])) ? trim($_GET['id']) : '';

if(!$id_mahasiswa) {
	$ada_error = 'Maaf, data tidak dapat diproses.';
} else {
	$query = $pdo->prepare('SELECT * FROM mahasiswa WHERE id_mahasiswa = :id_mahasiswa');
	$query->execute(array('id_mahasiswa' => $id_mahasiswa));
	$result = $query->fetch();
	
	if(empty($result)) {
		$ada_error = 'Maaf, data tidak dapat diproses.';
	}
}
?>

<?php
$judul_page = 'Detail mahasiswa';
require_once('template-parts/header.php');
?>

	<div class="main-content-row">
	<div class="container clearfix">
	
		<?php include_once('template-parts/sidebar-mahasiswa.php'); ?>
	
		<div class="main-content the-content">
			<h1><?php echo $judul_page; ?></h1>
			
			<?php if($ada_error): ?>
			
				<?php echo '<p>'.$ada_error.'</p>'; ?>
				
			<?php elseif(!empty($result)): ?>
			
				<h4>Nama Mahasiswa</h4>
				<p><?php echo $result['nama_mahasiswa']; ?></p>
				
				<h4>Angkatan</h4>
				<p><?php echo nl2br($result['angkatan']); ?></p>
				
				<h4>Tanggal Input</h4>
				<p><?php
					$tgl = strtotime($result['tanggal_input']);
					echo date('j F Y', $tgl);
				?></p>
				
				<?php
				$query2 = $pdo->prepare('SELECT nilai_mahasiswa.nilai AS nilai, kriteria.nama AS nama FROM kriteria 
				LEFT JOIN nilai_mahasiswa ON nilai_mahasiswa.id_kriteria = kriteria.id_kriteria 
				AND nilai_mahasiswa.id_mahasiswa = :id_mahasiswa ORDER BY kriteria.urutan_order ASC');
				$query2->execute(array(
					'id_mahasiswa' => $id_mahasiswa
				));
				$query2->setFetchMode(PDO::FETCH_ASSOC);
				$kriterias = $query2->fetchAll();
				if(!empty($kriterias)):
				?>
					<h3>Nilai Kriteria</h3>
					<table class="pure-table">
						<thead>
							<tr>
								<?php foreach($kriterias as $kriteria ): ?>
									<th><?php echo $kriteria['nama']; ?></th>
								<?php endforeach; ?>
							</tr>
						</thead>
						<tbody>
							<tr>
								<?php foreach($kriterias as $kriteria ): ?>
									<th><?php echo ($kriteria['nilai']) ? $kriteria['nilai'] : 0; ?></th>
								<?php endforeach; ?>
							</tr>
						</tbody>
					</table>
				<?php
				endif;
				?>

				<p><a href="edit-mahasiswa.php?id=<?php echo $id_mahasiswa; ?>" class="button"><span class="fa fa-pencil"></span> Edit</a> &nbsp; <a href="hapus-mahasiswa.php?id=<?php echo $id_mahasiswa; ?>" class="button button-red yaqin-hapus"><span class="fa fa-times"></span> Hapus</a></p>
			
			<?php endif; ?>			
			
		</div>
	
	</div><!-- .container -->
	</div><!-- .main-content-row -->


<?php
require_once('template-parts/footer.php');