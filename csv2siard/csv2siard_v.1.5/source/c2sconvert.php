<?
// Report all PHP errors
error_reporting(E_ALL);

// -----------------------------------------------------------------------------
// process the first CSV line and check and count column names
function processCSVColumnNames($buffer, $file, $table, $input) {
global $prg_option;

	$fct = 0;
	// check for column names
	foreach ($table['_c']['column'] as $column) {
		if (is_array($column)) {
			// multiple columns or only one column
			$name = (array_key_exists('_a', $column)) ? $column['_a']['name'] : $column['name'];
			$colname = trim($buffer[$fct]);
			if (!testDBMSNaming($name)) {
				$cn = $fct + 1;
				echo "\nColumn no $cn '$name' does not confirm with SQL naming convention";
				$prg_option['ERR'] = 32;
			}
			if ($prg_option['CHECK_NAMES'] and strcasecmp($name, $colname) != 0) {
				if ($colname == '') {
					echo "\nColumn '$name' in database model is missing in CSV file $file";
				} else {
					echo "\nColumn '$name' in database model does not confirm with column '$buffer[$fct]' in CSV file $file";
				}
				$prg_option['ERR'] = 32;
				return(false);
			}
			$fct++;
		}
	}
	
	// check column count
	$buf = array_chunk($input, 1);
	$ict = count($buf)-1;
	$ict = (trim($buf[$ict][0]) != '') ? $ict+1 : $ict;
	if ($fct != $ict) {
		echo "\nTo many columns in CSV file $file"; $prg_option['ERR'] = 27; return(false);
	}
	return(true);
}
// -----------------------------------------------------------------------------
// -----------------------------------------------------------------------------
// write header for SIARD XML file
function writeSIARDHeader($siardhandle, $tablefolder) {
global $prg_option;
	fwrite ($siardhandle, "<?xml version=\"1.0\" encoding=\"utf-8\"?".">\n");
	fwrite ($siardhandle, "<table xsi:schemaLocation=\"http://www.admin.ch/xmlns/siard/1.0/$prg_option[SIARD_SCHEMA]/$tablefolder.xsd $tablefolder.xsd\"
		xmlns=\"http://www.admin.ch/xmlns/siard/1.0/$prg_option[SIARD_SCHEMA]/$tablefolder.xsd\"
		xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\">
	");
	return;
}
// -----------------------------------------------------------------------------
// process a single CSV line and write a <row> into SIARD XML file
function writeSIARDColumn($siardhandle, $buffer, $columcount, $rowcount, &$table){
global $prg_option;

	$columcount = ($columcount > count($buffer)) ? count($buffer) : $columcount;

	fwrite ($siardhandle, "<row>");
	
	for ($i=1; $i <= $columcount; $i++) {
		if (trim($buffer[$i-1]) != '') {
			$buf = $buffer[$i-1];
			// convert to XML characterset utf-8
			switch ($prg_option['CHARSET']) {
				case "ASCII":
					$buf = utf8_encode(ascii2ansi($buf)); break;
				case "ISO-8859-1":
					$buf = utf8_encode($buf); break;
				case "UTF-8":
					break;
			}
			// check field type and convert to XML type *** TO BE DONE ***
			// multiple columns or only one column
			$column = (array_key_exists($i-1, $table['_c']['column'])) ? $table['_c']['column'][$i-1] : $table['_c']['column'];
			$type = (array_key_exists('_a', $column)) ? $column['_a']['type'] : $column['type'];
			$buf = trim($buf);
			$b = $buf;
			switch ($type) {
				case "TINYINT":
				case "SMALLINT":
				case "INTEGER":
				case "BIGINT":
					if (!ctype_digit($buf)) {
						echo "\nInteger type convertion failed in row $rowcount, column $i => '$buf'"; $prg_option['ERR'] = 32;
					}
					break;
				case "FLOAT":
				case "REAL":
				case "DOUBLE":
					$buf = strtr ($buf, ',', '.');
					if (!is_numeric ($buf)) {
						echo "\nDouble type convertion failed in row $rowcount, column $i => '$b'"; $prg_option['ERR'] = 32;
					}
					break;
				case "NUMERIC":
				case "DECIMAL":
					$buf = strtr ($buf, ',', '.');
					if (!is_numeric ($buf)) {
						echo "\nDecimal type convertion failed in row $rowcount, column $i => '$b'"; $prg_option['ERR'] = 32;
					}
					break;
				case "DATE":
				case "TIMESTAMP":
				$td = convert2XMLdate($buf);
					if ($td['date'] == '') {
						echo "\nDate/time type convertion failed in row $rowcount, column $i => '$b'"; $prg_option['ERR'] = 32;
					} else {
						$buf = $td['date'].'.000000000Z';
					}
					break;
				case "TIME":
					$td = convert2XMLdate($buf);
					if ($td['date'] == '') {
						echo "\nDate/time type convertion failed in row $rowcount, column $i => '$b'"; $prg_option['ERR'] = 32;
					} else {
						$buf = substr($td['date'], 11);
					}
					break;
				case "CHAR":
				case "VARCHAR":
				case "LONGVARCHAR":
				case "CLOB":
					$buf = xml_encode($buf);
					break;
				case "BIT":
				case "BINARY":
					break;
				case "VARBINARY":
				case "LONGVARBINARY":
				case "BLOB":
					$buf = base64_encode($buf);
					break;
				case "DATALINK":
					break;
				case "BOOLEANINT":
				case "BOOLEANCHAR":
					$buf = (to_bool($buf)) ? 'true' : 'false';
					break;
				default:
					break;
			}
			// write a <column> into SIARD XML file
			$buf = '<c' . $i . '>' . $buf . '</c' . $i . '>';
			fwrite ($siardhandle, $buf);
		}
	}

	fwrite ($siardhandle, "</row>\n");
}
// -----------------------------------------------------------------------------
// write footer for SIARD XML file
function writeSIARDFooter($siardhandle){
	fwrite ($siardhandle, "</table>\n");
	return;
}
// -----------------------------------------------------------------------------
// -----------------------------------------------------------------------------
// write header for SIARD schema file
function writeSchemaHeader($siardhandle, $tablefolder) {
global $prg_option;

	fwrite ($siardhandle, "<?xml version=\"1.0\" encoding=\"utf-8\"?".">\n");
	fwrite ($siardhandle, "
		<xs:schema targetNamespace=\"http://www.admin.ch/xmlns/siard/1.0/$prg_option[SIARD_SCHEMA]/$tablefolder.xsd\"
			xmlns:xs=\"http://www.w3.org/2001/XMLSchema\"
			xmlns=\"http://www.admin.ch/xmlns/siard/1.0/$prg_option[SIARD_SCHEMA]/$tablefolder.xsd\"
			elementFormDefault=\"qualified\"
			attributeFormDefault=\"unqualified\">
		<xs:element name=\"table\">
			<xs:complexType>
				<xs:sequence>
					<xs:element name=\"row\" type=\"rowType\" minOccurs=\"0\" maxOccurs=\"unbounded\"/>
				</xs:sequence>
			</xs:complexType>
		</xs:element>
		<xs:complexType name=\"rowType\">
		<xs:sequence>
	");
	return;
}
// -----------------------------------------------------------------------------
// write content SIARD schema file
function writeSchemaContent($siardhandle, &$table){
	$colcount = 1;
	foreach ($table['_c']['column'] as $column) {
		if (is_array($column)) {
			// multiple columns or only one column
			$type = (array_key_exists('_a', $column)) ? $column['_a']['type'] : $column['type'];
			switch ($type) {
				case "TINYINT":
				case "SMALLINT":
				case "INTEGER":
				case "BIGINT":
					$xstype = 'integer'; break;
				case "FLOAT":
				case "REAL":
				case "DOUBLE":
					$xstype = 'double'; break;
				case "NUMERIC":
				case "DECIMAL":
					$xstype = 'decimal'; break;
				case "DATE":
				case "TIMESTAMP":
					$xstype = 'dateTime'; break;
				case "TIME":
					$xstype = 'time'; break;
				case "CHAR":
				case "VARCHAR":
				case "LONGVARCHAR":
				case "CLOB":
					$xstype = 'string'; break;
				case "BIT":
				case "BINARY":
					$xstype = 'byte'; break;
				case "VARBINARY":
				case "LONGVARBINARY":
				case "BLOB":
					$xstype = 'base64Binary'; break;
				case "DATALINK":
					$xstype = 'anyURI'; break;
				case "BOOLEANINT":
				case "BOOLEANCHAR":
					$xstype = 'boolean'; break;
				default:
					$xstype = 'string'; break;
			}
			fwrite ($siardhandle, "<xs:element name=\"c$colcount\" type=\"xs:$xstype\" minOccurs=\"0\"/>\n");
			$colcount++;
		}
	}
	return;
}
// -----------------------------------------------------------------------------
// write footer for SIARD schema file
function writeSchemaFooter($siardhandle){
	fwrite ($siardhandle, "
				</xs:sequence>
			</xs:complexType>
		</xs:schema>");
	return;
}
?>