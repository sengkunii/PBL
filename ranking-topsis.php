<?php
/* ---------------------------------------------
 * SPK TOPSIS
 * Author: Zunan Arif Rahmanto - 15111131
 * ------------------------------------------- */

/* ---------------------------------------------
 * Konek ke database & load fungsi-fungsi
 * ------------------------------------------- */
require_once('includes/init.php');

/* ---------------------------------------------
 * Load Header
 * ------------------------------------------- */
$judul_page = 'Perankingan Menggunakan Metode TOPSIS';
require_once('template-parts/header.php');

/* ---------------------------------------------
 * Set jumlah digit di belakang koma
 * ------------------------------------------- */
$digit = 4;

/* ---------------------------------------------
 * Fetch semua kriteria
 * ------------------------------------------- */
$query = $pdo->prepare('SELECT id_kriteria, nama, type, bobot
	FROM kriteria ORDER BY urutan_order ASC');
$query->execute();
$query->setFetchMode(PDO::FETCH_ASSOC);
$kriterias = $query->fetchAll();

/* ---------------------------------------------
 * Fetch semua mahasiswa (alternatif)
 * ------------------------------------------- */
$query2 = $pdo->prepare('SELECT id_mahasiswa, nama_mahasiswa FROM mahasiswa');
$query2->execute();			
$query2->setFetchMode(PDO::FETCH_ASSOC);
$mahasiswas = $query2->fetchAll();


/* >>> STEP 1 ===================================
 * Matrix Keputusan (X)
 * ------------------------------------------- */
$matriks_x = array();
foreach($kriterias as $kriteria):
	foreach($mahasiswas as $mahasiswa):
		
		$id_mahasiswa = $mahasiswa['id_mahasiswa'];
		$id_kriteria = $kriteria['id_kriteria'];
		
		// Fetch nilai dari db
		$query3 = $pdo->prepare('SELECT nilai FROM nilai_mahasiswa
			WHERE id_mahasiswa = :id_mahasiswa AND id_kriteria = :id_kriteria');
		$query3->execute(array(
			'id_mahasiswa' => $id_mahasiswa,
			'id_kriteria' => $id_kriteria,
		));			
		$query3->setFetchMode(PDO::FETCH_ASSOC);
		if($nilai_mahasiswa = $query3->fetch()) {
			// Jika ada nilai kriterianya
			$matriks_x[$id_kriteria][$id_mahasiswa] = $nilai_mahasiswa['nilai'];
		} else {			
			$matriks_x[$id_kriteria][$id_mahasiswa] = 0;
		}

	endforeach;
endforeach;

/* >>> STEP 3 ===================================
 * Matriks Ternormalisasi (R)
 * ------------------------------------------- */
$matriks_r = array();
foreach($matriks_x as $id_kriteria => $nilai_mahasiswas):
	
	// Mencari akar dari penjumlahan kuadrat
	$jumlah_kuadrat = 0;
	foreach($nilai_mahasiswas as $nilai_mahasiswa):
		$jumlah_kuadrat += pow($nilai_mahasiswa, 2);
	endforeach;
	$akar_kuadrat = sqrt($jumlah_kuadrat);
	
	// Mencari hasil bagi akar kuadrat
	// Lalu dimasukkan ke array $matriks_r
	foreach($nilai_mahasiswas as $id_mahasiswa => $nilai_mahasiswa):
		$matriks_r[$id_kriteria][$id_mahasiswa] = $nilai_mahasiswa / $akar_kuadrat;
	endforeach;
	
endforeach;


/* >>> STEP 4 ===================================
 * Matriks Y
 * ------------------------------------------- */
$matriks_y = array();
foreach($kriterias as $kriteria):
	foreach($mahasiswas as $mahasiswa):
		
		$bobot = $kriteria['bobot'];
		$id_mahasiswa = $mahasiswa['id_mahasiswa'];
		$id_kriteria = $kriteria['id_kriteria'];
		
		$nilai_r = $matriks_r[$id_kriteria][$id_mahasiswa];
		$matriks_y[$id_kriteria][$id_mahasiswa] = $bobot * $nilai_r;

	endforeach;
endforeach;


/* >>> STEP 5 ================================
 * Solusi Ideal Positif & Negarif
 * ------------------------------------------- */
$solusi_ideal_positif = array();
$solusi_ideal_negatif = array();
foreach($kriterias as $kriteria):

	$id_kriteria = $kriteria['id_kriteria'];
	$type_kriteria = $kriteria['type'];
	
	$nilai_max = max($matriks_y[$id_kriteria]);
	$nilai_min = min($matriks_y[$id_kriteria]);
	
	if($type_kriteria == 'benefit'):
		$s_i_p = $nilai_max;
		$s_i_n = $nilai_min;
	elseif($type_kriteria == 'cost'):
		$s_i_p = $nilai_min;
		$s_i_n = $nilai_max;
	endif;
	
	$solusi_ideal_positif[$id_kriteria] = $s_i_p;
	$solusi_ideal_negatif[$id_kriteria] = $s_i_n;

endforeach;


/* >>> STEP 6 ================================
 * Jarak Ideal Positif & Negatif
 * ------------------------------------------- */
$jarak_ideal_positif = array();
$jarak_ideal_negatif = array();
foreach($mahasiswas as $mahasiswa):

	$id_mahasiswa = $mahasiswa['id_mahasiswa'];		
	$jumlah_kuadrat_jip = 0;
	$jumlah_kuadrat_jin = 0;
	
	// Mencari penjumlahan kuadrat
	foreach($matriks_y as $id_kriteria => $nilai_mahasiswas):
		
		$hsl_pengurangan_jip = $nilai_mahasiswas[$id_mahasiswa] - $solusi_ideal_positif[$id_kriteria];
		$hsl_pengurangan_jin = $nilai_mahasiswas[$id_mahasiswa] - $solusi_ideal_negatif[$id_kriteria];
		
		$jumlah_kuadrat_jip += pow($hsl_pengurangan_jip, 2);
		$jumlah_kuadrat_jin += pow($hsl_pengurangan_jin, 2);
	
	endforeach;
	
	// Mengakarkan hasil penjumlahan kuadrat
	$akar_kuadrat_jip = sqrt($jumlah_kuadrat_jip);
	$akar_kuadrat_jin = sqrt($jumlah_kuadrat_jin);
	
	// Memasukkan ke array matriks jip & jin
	$jarak_ideal_positif[$id_mahasiswa] = $akar_kuadrat_jip;
	$jarak_ideal_negatif[$id_mahasiswa] = $akar_kuadrat_jin;
	
endforeach;


/* >>> STEP 7 ================================
 * Perangkingan
 * ------------------------------------------- */
$ranks = array();
foreach($mahasiswas as $mahasiswa):

	$s_negatif = $jarak_ideal_negatif[$mahasiswa['id_mahasiswa']];
	$s_positif = $jarak_ideal_positif[$mahasiswa['id_mahasiswa']];	
	
	$nilai_v = $s_negatif / ($s_positif + $s_negatif);
	
	$ranks[$mahasiswa['id_mahasiswa']]['id_mahasiswa'] = $mahasiswa['id_mahasiswa'];
	$ranks[$mahasiswa['id_mahasiswa']]['nama_mahasiswa'] = $mahasiswa['nama_mahasiswa'];
	$ranks[$mahasiswa['id_mahasiswa']]['nilai'] = $nilai_v;
	
endforeach;
 
?>

<div class="main-content-row">
<div class="container clearfix">	

	<div class="main-content main-content-full the-content">
		
		<h1><?php echo $judul_page; ?></h1>
		
		<!-- STEP 1. Matriks Keputusan(X) ==================== -->		
		<h3>Step 1: Matriks Keputusan (X)</h3>
		<table class="pure-table pure-table-striped">
			<thead>
				<tr class="super-top">
					<th rowspan="2" class="super-top-left">Nama Mahasiswa</th>
					<th colspan="<?php echo count($kriterias); ?>">Kriteria</th>
				</tr>
				<tr>
					<?php foreach($kriterias as $kriteria ): ?>
						<th><?php echo $kriteria['nama']; ?></th>
					<?php endforeach; ?>
				</tr>
			</thead>
			<tbody>
				<?php foreach($mahasiswas as $mahasiswa): ?>
					<tr>
						<td><?php echo $mahasiswa['nama_mahasiswa']; ?></td>
						<?php						
						foreach($kriterias as $kriteria):
							$id_mahasiswa = $mahasiswa['id_mahasiswa'];
							$id_kriteria = $kriteria['id_kriteria'];
							echo '<td>';
							echo $matriks_x[$id_kriteria][$id_mahasiswa];
							echo '</td>';
						endforeach;
						?>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
		
		<!-- STEP 2. Bobot Preferensi (W) ==================== -->
		<h3>Step 2: Bobot Preferensi (W)</h3>			
		<table class="pure-table pure-table-striped">
			<thead>
				<tr>
					<th>Nama Kriteria</th>
					<th>Type</th>
					<th>Bobot (W)</th>						
				</tr>
			</thead>
			<tbody>
				<?php foreach($kriterias as $hasil): ?>
					<tr>
						<td><?php echo $hasil['nama']; ?></td>
						<td>
						<?php
						if($hasil['type'] == 'benefit') {
							echo 'Benefit';
						} elseif($hasil['type'] == 'cost') {
							echo 'Cost';
						}							
						?>
						</td>
						<td><?php echo $hasil['bobot']; ?></td>							
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
		
		<!-- Step 3: Matriks Ternormalisasi (R) ==================== -->
		<h3>Step 3: Matriks Ternormalisasi (R)</h3>			
		<table class="pure-table pure-table-striped">
			<thead>
				<tr class="super-top">
					<th rowspan="2" class="super-top-left">Nama Mahasiswa</th>
					<th colspan="<?php echo count($kriterias); ?>">Kriteria</th>
				</tr>
				<tr>
					<?php foreach($kriterias as $kriteria ): ?>
						<th><?php echo $kriteria['nama']; ?></th>
					<?php endforeach; ?>
				</tr>
			</thead>
			<tbody>
				<?php foreach($mahasiswas as $mahasiswa): ?>
					<tr>
						<td><?php echo $mahasiswa['nama_mahasiswa']; ?></td>
						<?php						
						foreach($kriterias as $kriteria):
							$id_mahasiswa = $mahasiswa['id_mahasiswa'];
							$id_kriteria = $kriteria['id_kriteria'];
							echo '<td>';
							echo round($matriks_r[$id_kriteria][$id_mahasiswa], $digit);
							echo '</td>';
						endforeach;
						?>
					</tr>
				<?php endforeach; ?>				
			</tbody>
		</table>
		
		
		<!-- Step 4: Matriks Y ==================== -->
		<h3>Step 4: Matriks Y</h3>			
		<table class="pure-table pure-table-striped">
			<thead>
				<tr class="super-top">
					<th rowspan="2" class="super-top-left">Nama Mahasiswa</th>
					<th colspan="<?php echo count($kriterias); ?>">Kriteria</th>
				</tr>
				<tr>
					<?php foreach($kriterias as $kriteria ): ?>
						<th><?php echo $kriteria['nama']; ?></th>
					<?php endforeach; ?>
				</tr>
			</thead>
			<tbody>
				<?php foreach($mahasiswas as $mahasiswa): ?>
					<tr>
						<td><?php echo $mahasiswa['nama_mahasiswa']; ?></td>
						<?php						
						foreach($kriterias as $kriteria):
							$id_mahasiswa = $mahasiswa['id_mahasiswa'];
							$id_kriteria = $kriteria['id_kriteria'];
							echo '<td>';
							echo round($matriks_y[$id_kriteria][$id_mahasiswa], $digit);
							echo '</td>';
						endforeach;
						?>
					</tr>
				<?php endforeach; ?>	
			</tbody>
		</table>	
		
		
		<!-- Step 5.1: Solusi Ideal Positif ==================== -->
		<h3>Step 5.1: Solusi Ideal Positif (A<sup>+</sup>)</h3>			
		<table class="pure-table pure-table-striped">
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
						<td>
							<?php
							$id_kriteria = $kriteria['id_kriteria'];							
							echo round($solusi_ideal_positif[$id_kriteria], $digit);
							?>
						</td>
					<?php endforeach; ?>
				</tr>					
			</tbody>
		</table>
		
		<!-- Step 5.2: Solusi Ideal negative ==================== -->
		<h3>Step 5.2: Solusi Ideal Negatif (A<sup>-</sup>)</h3>			
		<table class="pure-table pure-table-striped">
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
						<td>
							<?php
							$id_kriteria = $kriteria['id_kriteria'];							
							echo round($solusi_ideal_negatif[$id_kriteria], $digit);
							?>
						</td>
					<?php endforeach; ?>
				</tr>					
			</tbody>
		</table>		
		
		<!-- Step 6.1: Jarak Ideal Positif ==================== -->
		<h3>Step 6.1: Jarak Ideal Positif (S<sub>i</sub>+)</h3>			
		<table class="pure-table pure-table-striped">
			<thead>					
				<tr>
					<th class="super-top-left">Nama Mahasiswa</th>
					<th>Jarak Ideal Positif</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($mahasiswas as $mahasiswa ): ?>
					<tr>
						<td><?php echo $mahasiswa['nama_mahasiswa']; ?></td>
						<td>
							<?php								
							$id_mahasiswa = $mahasiswa['id_mahasiswa'];
							echo round($jarak_ideal_positif[$id_mahasiswa], $digit);
							?>
						</td>						
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
		
		<!-- Step 6.2: Jarak Ideal Negatif ==================== -->
		<h3>Step 6.2: Jarak Ideal Negatif (S<sub>i</sub>-)</h3>			
		<table class="pure-table pure-table-striped">
			<thead>					
				<tr>
					<th class="super-top-left">Nama Mahasiswa</th>
					<th>Jarak Ideal Negatif</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($mahasiswas as $mahasiswa ): ?>
					<tr>
						<td><?php echo $mahasiswa['nama_mahasiswa']; ?></td>
						<td>
							<?php								
							$id_mahasiswa = $mahasiswa['id_mahasiswa'];
							echo round($jarak_ideal_negatif[$id_mahasiswa], $digit);
							?>
						</td>						
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
		
		
		<!-- Step 7: Perangkingan ==================== -->
		<?php		
		$sorted_ranks = $ranks;	
		
		// Sorting
		if(function_exists('array_multisort')):
			foreach ($sorted_ranks as $key => $row) {
				$nama_mahasiswa[$key]  = $row['nama_mahasiswa'];
				$nilai[$key] = $row['nilai'];
			}
			array_multisort($nilai, SORT_DESC, $nama_mahasiswa, SORT_ASC, $sorted_ranks);
		endif;
		?>		
		<h3>Step 7: Perangkingan (V)</h3>			
		<table class="pure-table pure-table-striped">
			<thead>					
				<tr>
					<th>Rangking</th>
					<th class="super-top-left">Nama Mahasiswa</th>
					<th>Nilai</th>
				</tr>
			</thead>
			<tbody>
				<?php 
				$no =1;
				foreach($sorted_ranks as $mahasiswa ): ?>
					
					<tr>
						<td><?php echo $no++; ?></td>
						<td><?php echo $mahasiswa['nama_mahasiswa']; ?></td>
						<td><?php echo round($mahasiswa['nilai'], $digit); ?></td>											
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>			
		
	</div>

</div><!-- .container -->
</div><!-- .main-content-row -->

<?php
require_once('template-parts/footer.php');