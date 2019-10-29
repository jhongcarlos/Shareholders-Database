<?php
// pdf
function fetch_data()
{
  $conn_info = array("Database" => "test_db");
  $db = sqlsrv_connect('MPIC-BACKUP-02', $conn_info);
  $corp_name = $_POST['corp_name'];
  $output = '';
  $sql = "SELECT * FROM dbo.tbl_shareholder WHERE CONVERT(VARCHAR(MAX), is_deleted) = '0'";
  $stmt = sqlsrv_query($db, $sql);

  $output .= '
        <table align="center" border="1">
        <tr>
        <th><b>ID</b></th>
        <th><b>Shareholder Name</b></th>
        <th><b>Type of Share</b></th>
        <th><b>Shares Owned</b></th>
        </tr>
        ';
  while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    if (strpos($row['company_affiliation'], $corp_name) !== false) {
      $arr = explode(",", $row['type_of_shares']);
      $arr2 = explode("|", $row['shares_owned']);
      $arr3 = explode(",",$row['company_affiliation']);
      $position = "";
      foreach ($arr3 as $key => $value) {
      if($value == $corp_name){
      $position = $key;
        }
      }
      $output .= '
                <tr>
                <td>' . $row['ID'] . '</td> 
                <td>' . $row['first_name'] . ' ' . $row['last_name'] .'</td>
                <td>' . $arr[$position] . '</td>
                <td>' . $arr2[$position] . '</td>
                </tr>';
    }
  }

  $output .= '
            
  </table>
  </div>
  ';
  return $output;
}
if (isset($_POST["pdf"])) {
  $corp_name = $_POST['corp_name'];
  require_once('tcpdf/tcpdf.php');
  $obj_pdf = new TCPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
  $obj_pdf->SetCreator(PDF_CREATOR);
  $obj_pdf->SetTitle($corp_name . ' - Report');
  $obj_pdf->SetHeaderData('', '', PDF_HEADER_TITLE, PDF_HEADER_STRING);
  $obj_pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
  $obj_pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
  $obj_pdf->SetDefaultMonospacedFont('helvetica');
  $obj_pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
  $obj_pdf->SetMargins(PDF_MARGIN_LEFT, '10', PDF_MARGIN_RIGHT);
  $obj_pdf->setPrintHeader(false);
  $obj_pdf->setPrintFooter(false);
  $obj_pdf->SetAutoPageBreak(TRUE, 10);
  $obj_pdf->SetFont('helvetica', '', 11);
  $obj_pdf->AddPage();
  $content = '';
  $content .= '  
              <div align="center">
              <h1>'.$corp_name.'</h1>
              ';
  $content .= fetch_data();
  // $content .= '</table>';  
  $obj_pdf->writeHTML($content);
  ob_end_clean();
  $obj_pdf->Output('file.pdf', 'I');
}
