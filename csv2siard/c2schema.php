<?
// Report all PHP errors
error_reporting(E_ALL);

function loadSchema() {
global $torque_schema, $siard_schema, $siard2html, $torque2siard, $torque2csvschema;
log_echo("Load all XML schema into array and write to c2schema.php.php\n");
	$schema = array();
	$schema['torque_schema'] = file_get_contents($torque_schema);
	$schema['siard_schema'] = file_get_contents($siard_schema);
	$schema['siard2html'] = file_get_contents($siard2html);
	$schema['torque2siard'] = file_get_contents($torque2siard);
	$schema['torque2csvschema'] = file_get_contents($torque2csvschema);
	
	$export = var_export($schema, true);
	file_put_contents('c2schema.php.php', $export);
}
function unloadSchema() {
global $torque_schema, $siard_schema, $siard2html, $torque2siard, $torque2csvschema, 
		$static_torque_schema, $static_siard_schema, $static_siard2html, $static_torque2siard, $static_torque2csvschema;
log_echo("Unload all XML schema\n");
	file_put_contents($torque_schema, $static_torque_schema);
	file_put_contents($siard_schema, $static_siard_schema);
	file_put_contents($siard2html, $static_siard2html);
	file_put_contents($torque2siard, $static_torque2siard);
	file_put_contents($torque2csvschema, $static_torque2csvschema);
}

// -----------------------------------------------------------------------------
$static_torque_schema = '<?xml version="1.0" encoding="UTF-8"?>
<!--
 Licensed to the Apache Software Foundation (ASF) under one
 or more contributor license agreements.  See the NOTICE file
 distributed with this work for additional information
 regarding copyright ownership.  The ASF licenses this file
 to you under the Apache License, Version 2.0 (the
 "License"); you may not use this file except in compliance
 with the License.  You may obtain a copy of the License at

   http://www.apache.org/licenses/LICENSE-2.0

 Unless required by applicable law or agreed to in writing,
 software distributed under the License is distributed on an
 "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY
 KIND, either express or implied.  See the License for the
 specific language governing permissions and limitations
 under the License.
-->
<!--
    Torque XML database schema DTD
    $Id: c2schema.php 190 2013-06-11 13:08:30Z U80789367 $
-->
<xs:schema targetNamespace="http://db.apache.org/torque/4.0/templates/database" xmlns:xs="http://www.w3.org/2001/XMLSchema" xmlns="http://db.apache.org/torque/4.0/templates/database" elementFormDefault="qualified" version="4.0">
	<xs:annotation>
		<xs:documentation xml:lang="en">
The XML schema used by version 4.0 and greater of the Apache Software
Foundation Torque project(
<a href="http://db.apache.org/torque">http://db.apache.org/torque</a> )
to model SQL database information. This model is used by various Torque
utilities for managing the SQL Server info and to build the Java objects
to access this data.

The basic structure of a model is built using the database element
as the root.  This will contain references to options, external (include)
models, new SQL Domain definitions, and tables.  See the Torque project
home page for more details.
    </xs:documentation>
	</xs:annotation>
	<!-- =====================================
     database element definition
     ===================================== -->
	<xs:element name="database" type="databaseType">
		<xs:annotation>
			<xs:documentation xml:lang="en">
The root element for defining a Torque database schema.
      </xs:documentation>
		</xs:annotation>
	</xs:element>
	<xs:complexType name="databaseType">
		<xs:annotation>
			<xs:documentation xml:lang="en">
The root type definition for a Torque database schema.
      </xs:documentation>
		</xs:annotation>
		<xs:sequence>
			<xs:element name="option" type="optionType" minOccurs="0" maxOccurs="unbounded">
				<xs:annotation>
					<xs:documentation xml:lang="en">
A set of key/value options to be passed to custom generator templates.
          </xs:documentation>
				</xs:annotation>
			</xs:element>
			<xs:element name="external-schema" type="externalSchemaType" minOccurs="0" maxOccurs="unbounded">
				<xs:annotation>
					<xs:documentation xml:lang="en">
Include another schema file.
          </xs:documentation>
				</xs:annotation>
			</xs:element>
			<xs:element name="domain" type="domainType" minOccurs="0" maxOccurs="unbounded">
				<xs:annotation>
					<xs:documentation xml:lang="en">
Domains are used to define common attribute sets for columns.
          </xs:documentation>
				</xs:annotation>
			</xs:element>
			<xs:element name="table" type="tableType" maxOccurs="unbounded">
				<xs:annotation>
					<xs:documentation xml:lang="en">
Define table with its relevant attributes.
          </xs:documentation>
				</xs:annotation>
			</xs:element>
		</xs:sequence>
		<xs:attribute name="name" type="javaNameType" use="required">
			<xs:annotation>
				<xs:documentation xml:lang="en">
The name used to identify this schema in the generated
Java objects and as the default JDBC connection pool to use.
        </xs:documentation>
			</xs:annotation>
		</xs:attribute>
		<xs:attribute name="package" type="javaQualifiedNameType" use="optional">
			<xs:annotation>
				<xs:documentation xml:lang="en">
The base Java package to use for the Java classes generated by this schema.
This overrides the targetPackage property in the Torque build.properties file.
        </xs:documentation>
			</xs:annotation>
		</xs:attribute>
		<xs:attribute name="baseClass" type="javaQualifiedNameType" use="optional">
			<xs:annotation>
				<xs:documentation xml:lang="en">
The fully qualified class that the generated Java table record objects will
extend. This class does not have to extend org.apache.torque.om.BaseObject.
        </xs:documentation>
			</xs:annotation>
		</xs:attribute>
		<xs:attribute name="basePeer" type="javaQualifiedNameType" use="optional">
			<xs:annotation>
				<xs:documentation xml:lang="en">
The fully qualified class that the generated Java Peer objects will extend.
Unlike baseClass, basePeer should extend BasePeer at some point in the chain,
i.e. it needs to be the superclass.
        </xs:documentation>
			</xs:annotation>
		</xs:attribute>
		<xs:attribute name="defaultJavaNamingMethod" type="namingMethodType" use="optional" default="underscore">
			<xs:annotation>
				<xs:documentation xml:lang="en">
This attribute determines how table or column names, from the name
attribute of the table or column element, are converted to a Java
class or method name respectively when creating the OM Java 
objects. There are three different options:

- nochange
    Indicates no change is performed
- underscore
    Underscores are removed, First letter is capitalized,
    first letter after an underscore is capitalized, the
    rest of the letters are converted to lowercase.
- javaname
    Same as underscore, but no letters are converted to lowercase.
- null
    Use the value previously set or the default value.
        </xs:documentation>
			</xs:annotation>
		</xs:attribute>
		<xs:attribute name="defaultJavaType" type="javaReturnType" use="optional" default="primitive">
			<xs:annotation>
				<xs:documentation xml:lang="en">
Defines if the record object property getter / setters will 
use objects (e.g. Integer) or primitives (e.g. int), defaults
to primitive.
        </xs:documentation>
			</xs:annotation>
		</xs:attribute>
		<xs:attribute name="defaultIdMethod" type="idMethodType" use="optional" default="none">
			<xs:annotation>
				<xs:documentation xml:lang="en">
Defines the defaultIdMethod to use with tables which do not have an idMethod
attribute defined. This attribute has 3 possible values, they are:

- idbroker
    Torque\'s software based id broker system
- native
    The SQL Server\'s native autoincrement/identifier process
- none
    Don\'t try to auto assign id numbers
- null
    Use the value previously set or the default value.
        </xs:documentation>
			</xs:annotation>
		</xs:attribute>
	</xs:complexType>
	<!-- =====================================
     option element definition
     ===================================== -->
	<xs:complexType name="optionType">
		<xs:annotation>
			<xs:documentation xml:lang="en">
These tags allows a set of key/value options to be passed to custom generator
templates.
      </xs:documentation>
		</xs:annotation>
		<xs:attribute name="key" type="xs:string" use="required"/>
		<xs:attribute name="value" type="xs:string" use="required"/>
	</xs:complexType>
	<!-- =====================================
     external-schema element definition
     ===================================== -->
	<xs:complexType name="externalSchemaType">
		<xs:annotation>
			<xs:documentation xml:lang="en">
Includes another schema file.
      </xs:documentation>
		</xs:annotation>
		<xs:attribute name="filename" type="xs:string" use="required"/>
	</xs:complexType>
	<!-- =====================================
     domain element definition
     ===================================== -->
	<xs:complexType name="domainType">
		<xs:annotation>
			<xs:documentation xml:lang="en">
Domains are used to define attributes for columns.
      </xs:documentation>
		</xs:annotation>
		<xs:attribute name="name" type="xs:string" use="required">
			<xs:annotation>
				<xs:documentation xml:lang="en">
The name used to reference this set of column attributes.
        </xs:documentation>
			</xs:annotation>
		</xs:attribute>
		<xs:attribute name="type" type="sqlDataType" use="optional" default="VARCHAR">
			<xs:annotation>
				<xs:documentation xml:lang="en">
The SQL Standard data type for the column
        </xs:documentation>
			</xs:annotation>
		</xs:attribute>
		<xs:attribute name="size" type="xs:decimal" use="optional">
			<xs:annotation>
				<xs:documentation xml:lang="en">
The size of the field. E.g. Varchar(size) or Numeric(Size). Note that 
while this still supports the original torque use of using a
decimal number (5.2) to indicate the precision
and scale in one attribute. Use of the scale attribute is preferred.
        </xs:documentation>
			</xs:annotation>
		</xs:attribute>
		<xs:attribute name="scale" type="xs:integer" use="optional">
			<xs:annotation>
				<xs:documentation xml:lang="en">
The scale of the field.  E.g.decimal(size, scale)
        </xs:documentation>
			</xs:annotation>
		</xs:attribute>
		<xs:attribute name="default" type="xs:string" use="optional">
			<xs:annotation>
				<xs:documentation xml:lang="en">
The default column value
        </xs:documentation>
			</xs:annotation>
		</xs:attribute>
		<xs:attribute name="description" type="xs:string" use="optional">
			<xs:annotation>
				<xs:documentation xml:lang="en">
The description of this domain for documentation purposes.
        </xs:documentation>
			</xs:annotation>
		</xs:attribute>
	</xs:complexType>
	<!-- =====================================
     table element definition
     ===================================== -->
	<xs:complexType name="tableType">
		<xs:annotation>
			<xs:documentation xml:lang="en">
The table element and its relevant attributes.
      </xs:documentation>
		</xs:annotation>
		<xs:sequence>
			<xs:element name="option" type="optionType" minOccurs="0" maxOccurs="unbounded"/>
			<xs:element name="column" type="columnType" maxOccurs="unbounded">
				<xs:annotation>
					<xs:documentation xml:lang="en">
The column element and its relevant attributes
          </xs:documentation>
				</xs:annotation>
			</xs:element>
			<xs:choice minOccurs="0" maxOccurs="unbounded">
				<xs:element name="foreign-key" type="foreignKeyType">
					<xs:annotation>
						<xs:documentation xml:lang="en">
Define a foreign key constraint for this table.
            </xs:documentation>
					</xs:annotation>
				</xs:element>
				<xs:element name="index" type="indexType">
					<xs:annotation>
						<xs:documentation xml:lang="en">
Defines an index for this table.
            </xs:documentation>
					</xs:annotation>
				</xs:element>
				<xs:element name="unique" type="uniqueType">
					<xs:annotation>
						<xs:documentation xml:lang="en">
Define a unique value constraint
            </xs:documentation>
					</xs:annotation>
				</xs:element>
				<xs:element name="id-method-parameter" type="idMethodParameterType"/>
			</xs:choice>
		</xs:sequence>
		<xs:attribute name="name" type="sqlNameType" use="required">
			<xs:annotation>
				<xs:documentation xml:lang="en">
The table name of the SQL table.
        </xs:documentation>
			</xs:annotation>
		</xs:attribute>
		<xs:attribute name="interface" type="javaQualifiedNameType" use="optional">
			<xs:annotation>
				<xs:documentation xml:lang="en">
The interface attribute specifies an interface that should be referenced in
the implements section of the generated extension class. If this is a fully
qualified class name (i. e. the string contains dots), the interface will
simply be implemented by the extension object. If the interface is a simple
class name (without dots), an empty interface file will be generated in the
extension object package. When this attribute is used, all methods that
normally would return the extension object type will now return the interface
type. This feature allows to use Torque generated classes in the context of
other applications or APIs.
        </xs:documentation>
			</xs:annotation>
		</xs:attribute>
		<xs:attribute name="baseClass" type="javaQualifiedNameType" use="optional">
			<xs:annotation>
				<xs:documentation xml:lang="en">
The fully qualified class that the generated Java table
record objects will extend. This class does not have to extend 
org.apache.torque.om.BaseObject.
        </xs:documentation>
			</xs:annotation>
		</xs:attribute>
		<xs:attribute name="basePeer" type="javaQualifiedNameType" use="optional">
			<xs:annotation>
				<xs:documentation xml:lang="en">
The fully qualified class that the generated Java Peer objects will extend.
Unlike baseClass, basePeer should extend BasePeer at some point in the chain,
i.e. it needs to be the superclass.
        </xs:documentation>
			</xs:annotation>
		</xs:attribute>
		<xs:attribute name="idMethod" type="idMethodType" use="optional">
			<xs:annotation>
				<xs:documentation xml:lang="en">
Defines the id method to automatically generate ids for this table.
This attribute has 3 possible values, they are:

- idbroker
    Torque\'s software based id broker system
- native
    The SQL Server\'s native autoincrement / identifier process
- none
    Don\'t try to auto assign id numbers
        </xs:documentation>
			</xs:annotation>
		</xs:attribute>
		<xs:attribute name="abstract" type="xs:boolean" use="optional" default="false">
			<xs:annotation>
				<xs:documentation xml:lang="en">
Whether or not to generate the class as Abstract or not
        </xs:documentation>
			</xs:annotation>
		</xs:attribute>
		<xs:attribute name="javaName" type="javaNameType" use="optional">
			<xs:annotation>
				<xs:documentation xml:lang="en">
This is the Java class name to use when generating the Table or column. If
this is missing the Java name is generated base on the Java Naming Method
setting.
        </xs:documentation>
			</xs:annotation>
		</xs:attribute>
		<xs:attribute name="skipSql" type="xs:boolean" use="optional" default="false">
			<xs:annotation>
				<xs:documentation xml:lang="en">
Whether or not to skip SQL generation for this reference.  Useful for using
Views or creating a "subset" of columns in an existing table.
        </xs:documentation>
			</xs:annotation>
		</xs:attribute>
		<xs:attribute name="description" type="xs:string" use="optional">
			<xs:annotation>
				<xs:documentation xml:lang="en">
A description of this table.  Used for documentation and will be included in
the table generation SQL if the server type supports this.
        </xs:documentation>
			</xs:annotation>
		</xs:attribute>
		<xs:attribute name="javaNamingMethod" type="namingMethodType" use="optional">
			<xs:annotation>
				<xs:documentation xml:lang="en">
This attribute determines how the table and column names in this table
definition are converted to a Java class or method name respectively when
creating the OM Java objects. There are four different options:

- nochange
    Indicates no change is performed
- underscore
    Underscores are removed, First letter is capitalized, first letter
after an underscore is capitalized, the rest of the letters are 
converted to lowercase.
- javaname
    Same as underscore, but no letters are converted to lowercase.
- null
    Use the value previously set or the default value.
        </xs:documentation>
			</xs:annotation>
		</xs:attribute>
	</xs:complexType>
	<!-- =====================================
     column element definition
     ===================================== -->
	<xs:complexType name="columnType">
		<xs:annotation>
			<xs:documentation xml:lang="en">
The column element and its relevant attributes
      </xs:documentation>
		</xs:annotation>
		<xs:sequence>
			<xs:element name="option" type="optionType" minOccurs="0" maxOccurs="unbounded"/>
			<xs:element name="inheritance" type="inheritanceType" minOccurs="0" maxOccurs="unbounded">
				<xs:annotation>
					<xs:documentation xml:lang="en">
Define an inheritance mapping of records to class by a key column.  See the
inheritance How To document.
          </xs:documentation>
				</xs:annotation>
			</xs:element>
		</xs:sequence>
		<xs:attribute name="name" type="sqlNameType" use="required">
			<xs:annotation>
				<xs:documentation xml:lang="en">
The column name
        </xs:documentation>
			</xs:annotation>
		</xs:attribute>
		<xs:attribute name="type" type="sqlDataType" use="optional" default="VARCHAR">
			<xs:annotation>
				<xs:documentation xml:lang="en">
The SQL Standard data type for the column
        </xs:documentation>
			</xs:annotation>
		</xs:attribute>
		<xs:attribute name="size" type="xs:decimal" use="optional">
			<xs:annotation>
				<xs:documentation xml:lang="en">
The size of the field. E.g. Varchar(size) or Numeric(Size). Note that 
while this still supports the original torque use of using a
decimal number (5.2) to indicate the precision
and scale in one attribute. Use of the scale attribute is preferred.
        </xs:documentation>
			</xs:annotation>
		</xs:attribute>
		<xs:attribute name="scale" type="xs:integer" use="optional">
			<xs:annotation>
				<xs:documentation xml:lang="en">
The scale of the field.  E.g.decimal(size, scale)
        </xs:documentation>
			</xs:annotation>
		</xs:attribute>
		<xs:attribute name="default" type="xs:string" use="optional">
			<xs:annotation>
				<xs:documentation xml:lang="en">
The default column value
        </xs:documentation>
			</xs:annotation>
		</xs:attribute>
		<xs:attribute name="primaryKey" type="xs:boolean" use="optional" default="false">
			<xs:annotation>
				<xs:documentation xml:lang="en">
Is this column a primary key or not (true or false, defaults to false)
        </xs:documentation>
			</xs:annotation>
		</xs:attribute>
		<xs:attribute name="autoIncrement" type="xs:boolean" use="optional">
			<xs:annotation>
				<xs:documentation xml:lang="en">
Whether or not to auto-increment this field (true or false, defaults to false)
        </xs:documentation>
			</xs:annotation>
		</xs:attribute>
		<xs:attribute name="required" type="xs:boolean" use="optional" default="false">
			<xs:annotation>
				<xs:documentation xml:lang="en">
Whether a value is required in this column (NULL ALLOWED) (true or false,
defaults to false)
        </xs:documentation>
			</xs:annotation>
		</xs:attribute>
		<xs:attribute name="javaName" type="javaNameType" use="optional">
			<xs:annotation>
				<xs:documentation xml:lang="en">
The Java property name to use for this column in the record objects.
        </xs:documentation>
			</xs:annotation>
		</xs:attribute>
		<xs:attribute name="javaType" type="javaReturnType" use="optional">
			<xs:annotation>
				<xs:documentation xml:lang="en">
Defines if the record object property getter / setters will
use objects (e.g. Integer) or primitives (e.g. int), defaults 
to database attribute or primitive
        </xs:documentation>
			</xs:annotation>
		</xs:attribute>
		<xs:attribute name="domain" type="xs:string" use="optional">
			<xs:annotation>
				<xs:documentation xml:lang="en">
The domain reference name to set common settings.
        </xs:documentation>
			</xs:annotation>
		</xs:attribute>
		<xs:attribute name="inheritance" type="inheritanceAttrType" use="optional" default="false">
			<xs:annotation>
				<xs:documentation xml:lang="en">
The inheritance method used (see Inheritance How-To)
        </xs:documentation>
			</xs:annotation>
		</xs:attribute>
		<xs:attribute name="protected" type="xs:boolean" use="optional" default="false">
			<xs:annotation>
				<xs:documentation xml:lang="en">
If true, the setters and getters for this property will be protected rather
than public.
        </xs:documentation>
			</xs:annotation>
		</xs:attribute>
		<xs:attribute name="javaNamingMethod" type="javaNameType" use="optional">
			<xs:annotation>
				<xs:documentation xml:lang="en">
The method to use to convert the column name to a valid Java property name.
        </xs:documentation>
			</xs:annotation>
		</xs:attribute>
		<xs:attribute name="description" type="xs:string" use="optional">
			<xs:annotation>
				<xs:documentation xml:lang="en">
The description of this domain for documentation purposes.
        </xs:documentation>
			</xs:annotation>
		</xs:attribute>
	</xs:complexType>
	<!-- =====================================
     inheritance element definition
     ===================================== -->
	<xs:complexType name="inheritanceType">
		<xs:annotation>
			<xs:documentation xml:lang="en">
Define an inheritance mapping of records to class by a key column.  See the
inheritance How To document.
      </xs:documentation>
		</xs:annotation>
		<xs:attribute name="key" type="xs:string" use="required">
			<xs:annotation>
				<xs:documentation xml:lang="en">
A value found in the column marked as the inheritance key column
        </xs:documentation>
			</xs:annotation>
		</xs:attribute>
		<xs:attribute name="class" type="javaNameType" use="required">
			<xs:annotation>
				<xs:documentation xml:lang="en">
The class name for the object that will inherit the record values
        </xs:documentation>
			</xs:annotation>
		</xs:attribute>
		<xs:attribute name="extends" type="javaQualifiedNameType" use="optional">
			<xs:annotation>
				<xs:documentation xml:lang="en">
The class that the inheritor class will extend
        </xs:documentation>
			</xs:annotation>
		</xs:attribute>
	</xs:complexType>
	<!-- =====================================
     foreign-key element definition
     ===================================== -->
	<xs:complexType name="foreignKeyType">
		<xs:annotation>
			<xs:documentation xml:lang="en">
Define a foreign key constraint for this table.
      </xs:documentation>
		</xs:annotation>
		<xs:sequence>
			<xs:element name="option" type="optionType" minOccurs="0" maxOccurs="unbounded"/>
			<xs:element name="reference" type="referenceType" maxOccurs="unbounded">
				<xs:annotation>
					<xs:documentation xml:lang="en">
Define a mapping between a local column containing a foreign key value and
the foreign table column.
          </xs:documentation>
				</xs:annotation>
			</xs:element>
		</xs:sequence>
		<xs:attribute name="name" type="sqlNameType" use="optional">
			<xs:annotation>
				<xs:documentation xml:lang="en">
The name used to create the foreign key constraint.
        </xs:documentation>
			</xs:annotation>
		</xs:attribute>
		<xs:attribute name="foreignTable" type="sqlNameType" use="required">
			<xs:annotation>
				<xs:documentation xml:lang="en">
The name of the table that contains the foreign key
        </xs:documentation>
			</xs:annotation>
		</xs:attribute>
		<xs:attribute name="onDelete" type="cascadeType" use="optional">
			<xs:annotation>
				<xs:documentation xml:lang="en">
The action to take when the referenced value in foreignTable is deleted.
Note this is handled by the database server and not Torque code.  Will not
work if the DB server does not support this.
        </xs:documentation>
			</xs:annotation>
		</xs:attribute>
		<xs:attribute name="onUpdate" type="cascadeType" use="optional">
			<xs:annotation>
				<xs:documentation xml:lang="en">
The action to take when the referenced value in foreignTable is updated.
Note this is handled by the database server and not Torque code.  Will not
work if the DB server does not support this.
        </xs:documentation>
			</xs:annotation>
		</xs:attribute>
	</xs:complexType>
	<!-- =====================================
     reference element definition
     ===================================== -->
	<xs:complexType name="referenceType">
		<xs:annotation>
			<xs:documentation xml:lang="en">
Define a mapping between a local column containing a foreign key value and
the foreign table column.
      </xs:documentation>
		</xs:annotation>
		<xs:attribute name="foreign" type="sqlNameType" use="required">
			<xs:annotation>
				<xs:documentation xml:lang="en">
The column in the foreign table that contains the key.
        </xs:documentation>
			</xs:annotation>
		</xs:attribute>
		<xs:attribute name="local" type="sqlNameType" use="required">
			<xs:annotation>
				<xs:documentation xml:lang="en">
The column in this table that contains the foreign key.
        </xs:documentation>
			</xs:annotation>
		</xs:attribute>
	</xs:complexType>
	<!-- =====================================
     index element definition
     ===================================== -->
	<xs:complexType name="indexType">
		<xs:annotation>
			<xs:documentation xml:lang="en">
Defines an index for this table.
      </xs:documentation>
		</xs:annotation>
		<xs:sequence>
			<xs:element name="option" type="optionType" minOccurs="0" maxOccurs="unbounded"/>
			<xs:element name="index-column" type="indexColumnType" maxOccurs="unbounded">
				<xs:annotation>
					<xs:documentation xml:lang="en">
Define a column to use in a table index.
          </xs:documentation>
				</xs:annotation>
			</xs:element>
		</xs:sequence>
		<xs:attribute name="name" type="sqlNameType" use="optional">
			<xs:annotation>
				<xs:documentation xml:lang="en">
The name used in creating this index in the database.
        </xs:documentation>
			</xs:annotation>
		</xs:attribute>
	</xs:complexType>
	<!-- =====================================
     reference element definition
     ===================================== -->
	<xs:complexType name="indexColumnType">
		<xs:annotation>
			<xs:documentation xml:lang="en">
Define a column to use in a table index.
      </xs:documentation>
		</xs:annotation>
		<xs:attribute name="name" type="sqlNameType" use="required">
			<xs:annotation>
				<xs:documentation xml:lang="en">
A column name to use in this index.  Must exist in the table.
        </xs:documentation>
			</xs:annotation>
		</xs:attribute>
	</xs:complexType>
	<!-- =====================================
     unique element definition
     ===================================== -->
	<xs:complexType name="uniqueType">
		<xs:annotation>
			<xs:documentation xml:lang="en">
Define a unique value constraint
      </xs:documentation>
		</xs:annotation>
		<xs:sequence>
			<xs:element name="option" type="optionType" minOccurs="0" maxOccurs="unbounded"/>
			<xs:element name="unique-column" type="uniqueColumnType" maxOccurs="unbounded">
				<xs:annotation>
					<xs:documentation xml:lang="en">
Specify a column to use in the unique constraint.
          </xs:documentation>
				</xs:annotation>
			</xs:element>
		</xs:sequence>
		<xs:attribute name="name" type="sqlNameType" use="optional">
			<xs:annotation>
				<xs:documentation xml:lang="en">
The name to use in defining this constraint.
        </xs:documentation>
			</xs:annotation>
		</xs:attribute>
	</xs:complexType>
	<!-- =====================================
     unique-column element definition
     ===================================== -->
	<xs:complexType name="uniqueColumnType">
		<xs:annotation>
			<xs:documentation xml:lang="en">
Specify a column to use in the unique constraint.
      </xs:documentation>
		</xs:annotation>
		<xs:attribute name="name" type="sqlNameType" use="required">
			<xs:annotation>
				<xs:documentation xml:lang="en">
The name to use in defining this constraint.
        </xs:documentation>
			</xs:annotation>
		</xs:attribute>
	</xs:complexType>
	<!-- =====================================
     id-method-parameter element definition
     ===================================== -->
	<xs:complexType name="idMethodParameterType">
		<xs:attribute name="name" type="xs:string" use="optional" default="default"/>
		<xs:attribute name="value" type="xs:string" use="required"/>
	</xs:complexType>
	<!-- =====================================
    Type definitions for attributes
     ===================================== -->
	<xs:simpleType name="sqlDataType">
		<xs:annotation>
			<xs:documentation xml:lang="en">
Standard SQL column data types.
      </xs:documentation>
		</xs:annotation>
		<xs:restriction base="xs:NMTOKEN">
			<xs:enumeration value="BIT"/>
			<xs:enumeration value="TINYINT"/>
			<xs:enumeration value="SMALLINT"/>
			<xs:enumeration value="INTEGER"/>
			<xs:enumeration value="BIGINT"/>
			<xs:enumeration value="FLOAT"/>
			<xs:enumeration value="REAL"/>
			<xs:enumeration value="NUMERIC"/>
			<xs:enumeration value="DECIMAL"/>
			<xs:enumeration value="CHAR"/>
			<xs:enumeration value="VARCHAR"/>
			<xs:enumeration value="LONGVARCHAR"/>
			<xs:enumeration value="DATE"/>
			<xs:enumeration value="TIME"/>
			<xs:enumeration value="TIMESTAMP"/>
			<xs:enumeration value="BINARY"/>
			<xs:enumeration value="VARBINARY"/>
			<xs:enumeration value="LONGVARBINARY"/>
			<xs:enumeration value="NULL"/>
			<xs:enumeration value="OTHER"/>
			<xs:enumeration value="JAVA_OBJECT"/>
			<xs:enumeration value="DISTINCT"/>
			<xs:enumeration value="STRUCT"/>
			<xs:enumeration value="ARRAY"/>
			<xs:enumeration value="BLOB"/>
			<xs:enumeration value="CLOB"/>
			<xs:enumeration value="REF"/>
			<xs:enumeration value="BOOLEANINT"/>
			<xs:enumeration value="BOOLEANCHAR"/>
			<xs:enumeration value="DOUBLE"/>
		</xs:restriction>
	</xs:simpleType>
	<xs:simpleType name="idMethodType">
		<xs:annotation>
			<xs:documentation xml:lang="en">
A schema type for methods to create ids automatically.

idbroker = Torque\'s software based id broker system
native   = The SQL Server\'s native method, depends on database used
           (e.g. autoincrement for MySQL, sequence for postgresql...)
none     = Don\'t try to auto assign id numbers
      </xs:documentation>
		</xs:annotation>
		<xs:restriction base="xs:NMTOKEN">
			<xs:enumeration value="idbroker"/>
			<xs:enumeration value="native"/>
			<xs:enumeration value="none"/>
		</xs:restriction>
	</xs:simpleType>
	<xs:simpleType name="namingMethodType">
		<xs:annotation>
			<xs:documentation xml:lang="en">
This attribute determines how table or column names, from the name attribute
of the table or column element, are converted to a Java class or method
name respectively when creating the OM Java objects.
defaultJavaNamingMethod can contain four different values:

nochange   = Indicates no change is performed
underscore = Underscores are removed, First letter is capitalized,
             first letter after an underscore is capitalized, the
             rest of the letters are converted to lowercase.
javaname   = Same as underscore, but no letters are converted to lowercase.
null       = Use the value previously set or the default value.
      </xs:documentation>
		</xs:annotation>
		<xs:restriction base="xs:NMTOKEN">
			<xs:enumeration value="nochange"/>
			<xs:enumeration value="underscore"/>
			<xs:enumeration value="underscoreOmitSchema"/>
			<xs:enumeration value="javaname"/>
		</xs:restriction>
	</xs:simpleType>
	<xs:simpleType name="javaReturnType">
		<xs:restriction base="xs:NMTOKEN">
			<xs:enumeration value="object"/>
			<xs:enumeration value="primitive"/>
		</xs:restriction>
	</xs:simpleType>
	<xs:simpleType name="cascadeType">
		<xs:restriction base="xs:NMTOKEN">
			<xs:enumeration value="cascade"/>
			<xs:enumeration value="setnull"/>
			<xs:enumeration value="restrict"/>
		</xs:restriction>
	</xs:simpleType>
	<xs:simpleType name="inheritanceAttrType">
		<xs:restriction base="xs:string">
			<xs:enumeration value="single"/>
			<xs:enumeration value="false"/>
		</xs:restriction>
	</xs:simpleType>
	<xs:simpleType name="javaNameType">
		<xs:annotation>
			<xs:documentation xml:lang="en">
Java identifiers, e.g. [A-Za-z_$]A-Za-z_$0-9]*
      </xs:documentation>
		</xs:annotation>
		<xs:restriction base="xs:string"/>
	</xs:simpleType>
	<xs:simpleType name="javaQualifiedNameType">
		<xs:annotation>
			<xs:documentation xml:lang="en">
Java fully qualified names (e.g. x.y.x)
      </xs:documentation>
		</xs:annotation>
		<xs:restriction base="xs:string"/>
	</xs:simpleType>
	<xs:simpleType name="sqlNameType">
		<xs:annotation>
			<xs:documentation xml:lang="en">
SQL Standard non-delimited identifiers.
      </xs:documentation>
		</xs:annotation>
		<xs:restriction base="xs:string"/>
	</xs:simpleType>
</xs:schema>
';

// -----------------------------------------------------------------------------
$static_siard_schema = '<?xml version="1.0" encoding="utf-8" ?>
<!-- $Workfile: metadata.xsd $	*********************************** -->
<!-- Metadata schema for SIARD 1.0                                  -->
<!-- Version    : $Id: c2schema.php 190 2013-06-11 13:08:30Z U80789367 $ -->
<!-- Application: SIARD Suite                                       -->
<!--    Software-Independent Archival of Relational Databases       -->
<!-- Platform   : XML 1.0, XML Schema 2001                          -->
<!-- Description: This XML schema definition defines the structure  -->
<!--    of the metadata in the SIARD format                         -->
<!-- ************************************************************** -->
<!-- Copyright  :  2007, Swiss Federal Archives, Berne, Switzerland -->
<!-- ************************************************************** -->
<xs:schema id="metadata" 
  xmlns:xs="http://www.w3.org/2001/XMLSchema"
  xmlns="http://www.bar.admin.ch/xmlns/siard/1.0/metadata.xsd"
  targetNamespace="http://www.bar.admin.ch/xmlns/siard/1.0/metadata.xsd"
  elementFormDefault="qualified"
  attributeFormDefault="unqualified">

  <!-- root element of an XML file conforming to this XML schema -->
  <xs:element name="siardArchive">
    <xs:complexType>
      <xs:annotation>
        <xs:documentation>
          Root element of meta data of the SIARD archive
        </xs:documentation>
      </xs:annotation>
      <xs:sequence>
        <!-- name of the archived database -->
        <xs:element name="dbname" type="mandatoryString"/>
        <!-- short free form description of the database content -->
        <xs:element name="description" type="xs:string" minOccurs="0"/>
        <!-- name of person responsible for archiving the database -->
        <xs:element name="archiver" type="xs:string" minOccurs="0"/>
        <!-- contact data (telephone number or email address) of archiver -->
        <xs:element name="archiverContact" type="xs:string" minOccurs="0"/>
        <!-- name of data owner (section and institution responsible for data)
             of database when it was archived -->
        <xs:element name="dataOwner" type="mandatoryString"/>
        <!-- time span during which data where entered into the database -->
        <xs:element name="dataOriginTimespan" type="mandatoryString"/>
        <!-- name and version of program that generated the metadata file -->
        <xs:element name="producerApplication" type="xs:string" minOccurs="0"/>
        <!-- date of creation of archive (automatically generated by SIARD) -->
        <xs:element name="archivalDate" type="xs:date"/>
        <!-- message digest code over all primary data in folder "content" -->
        <xs:element name="messageDigest" type="digestType"/>
        <!-- DNS name of client machine from which SIARD was running for archiving -->
        <xs:element name="clientMachine" type="xs:string" minOccurs="0"/>
        <!-- name of database product and version from which database originates -->
        <xs:element name ="databaseProduct" type="xs:string" minOccurs="0"/>
        <!-- connection string used for archiving -->
        <xs:element name="connection" type="xs:string" minOccurs="0"/>
        <!-- database user used for archiving -->
        <xs:element name="databaseUser" type="xs:string" minOccurs="0"/>
        <!--  list of schemas in database  -->
        <xs:element name="schemas" type="schemasType"/>
        <!-- list of users in the archived database -->
        <xs:element name="users" type="usersType"/>
        <!-- list of roles in the archived database -->
        <xs:element name="roles" type="rolesType" minOccurs="0"/>
        <!-- list of privileges in the archived database -->
        <xs:element name="privileges" type="privilegesType" minOccurs="0"/>
      </xs:sequence>
      <!-- constraint: version number must be 1.0 -->
      <xs:attribute name="version" type="versionType" use="required" />
    </xs:complexType>
  </xs:element>

  <!-- complex type schemas -->
  <xs:complexType name="schemasType">
    <xs:annotation>
      <xs:documentation>
        List of schemas
      </xs:documentation>
    </xs:annotation>
    <xs:sequence>
      <xs:element name="schema" type="schemaType" minOccurs="1" maxOccurs="unbounded" />
    </xs:sequence>
  </xs:complexType>

  <!-- complex type schema -->
  <xs:complexType name="schemaType">
    <xs:annotation>
      <xs:documentation>
        Schema element in siardArchive
      </xs:documentation>
    </xs:annotation>
    <xs:sequence>
      <!-- database name of the schema -->
      <xs:element name="name" type="xs:string" />
      <!-- archive name of the schema folder -->
      <xs:element name="folder" type="fsName"/>
      <!-- description of the schema\'s meaning and content -->
      <xs:element name="description" type="xs:string" minOccurs="0"/>
      <!-- list of tables in the schema -->
      <xs:element name="tables" type="tablesType"/>
      <!-- list of views in the schema -->
      <xs:element name="views" type="viewsType" minOccurs="0"/>
      <!-- list of routines in the archived database -->
      <xs:element name="routines" type="routinesType" minOccurs="0"/>
    </xs:sequence>
  </xs:complexType>

  <!-- complex type tables -->
  <xs:complexType name="tablesType">
    <xs:annotation>
      <xs:documentation>
        List of tables
      </xs:documentation>
    </xs:annotation>
    <xs:sequence>
      <xs:element name="table" type="tableType" minOccurs="1" maxOccurs="unbounded" />
    </xs:sequence>
  </xs:complexType>

  <!-- complex type table -->
  <xs:complexType name="tableType">
    <xs:annotation>
      <xs:documentation>
        Table element in siardArchive
      </xs:documentation>
    </xs:annotation>
    <xs:sequence>
      <!-- database name of the table -->
      <xs:element name="name" type="xs:string"/>
      <!-- archive name of the table folder -->
      <xs:element name="folder" type="fsName"/>
      <!-- description of the table\'s meaning and content -->
      <xs:element name="description" type="xs:string" minOccurs="0"/>
      <!-- list of columns of the table -->
      <xs:element name="columns" type="columnsType"/>
      <!--  primary key -->
      <xs:element name="primaryKey" type="primaryKeyType" minOccurs="0"/>
      <!--  foreign keys  -->
      <xs:element name="foreignKeys" type="foreignKeysType" minOccurs="0"/>
      <!--  candidate keys (unique constraints)  -->
      <xs:element name="candidateKeys" type="candidateKeysType" minOccurs="0"/>
      <!-- list of (check) constraints -->
      <xs:element name="checkConstraints" type="checkConstraintsType" minOccurs="0"/>
      <!--  list of triggers  -->
      <xs:element name="triggers" type="triggersType" minOccurs="0"/>
      <!--  number of rows in the table -->
      <xs:element name="rows" type="xs:integer"/>
    </xs:sequence>
  </xs:complexType>

  <!-- complex type views -->
  <xs:complexType name="viewsType">
    <xs:annotation>
      <xs:documentation>
        List of views
      </xs:documentation>
    </xs:annotation>
    <xs:sequence>
      <xs:element name="view" type="viewType" minOccurs="1" maxOccurs="unbounded" />
    </xs:sequence>
  </xs:complexType>

  <!-- complex type view -->
  <xs:complexType name="viewType">
    <xs:annotation>
      <xs:documentation>
        View element in siardArchive
      </xs:documentation>
    </xs:annotation>
    <xs:sequence>
      <!-- database name of the view -->
      <xs:element name="name" type="xs:string" />
      <!-- SQL query string defining the view -->
      <xs:element name="query" type="xs:string" minOccurs="0"/>
      <!-- original query string defining the view -->
      <xs:element name="queryOriginal" type="xs:string" minOccurs="0"/>
      <!-- description of the view\'s meaning and content -->
      <xs:element name="description" type="xs:string" minOccurs="0"/>
      <!-- list of columns of the view -->
      <xs:element name="columns" type="columnsType"/>
    </xs:sequence>
  </xs:complexType>

  <!-- complex type columns -->
  <xs:complexType name="columnsType">
    <xs:annotation>
      <xs:documentation>
        List of columns
      </xs:documentation>
    </xs:annotation>
    <xs:sequence>
      <xs:element name="column" type="columnType" minOccurs="1" maxOccurs="unbounded" />
    </xs:sequence>
  </xs:complexType>

  <!-- complex type column -->
  <xs:complexType name="columnType">
    <xs:annotation>
      <xs:documentation>
        Column element in siardArchive
      </xs:documentation>
    </xs:annotation>
    <xs:sequence>
      <!-- database name of the column -->
      <xs:element name="name" type="xs:string" />
      <!-- archive name of the lob folder -->
      <xs:element name="folder" type="fsName" minOccurs="0"/>
      <!-- SQL:1999 data type of the column -->
      <xs:element name="type" type="xs:string" />
      <!-- original data type of the column -->
      <xs:element name="typeOriginal" type="xs:string" minOccurs="0"/>
      <!-- default value -->
      <xs:element name="defaultValue" type="xs:string" minOccurs="0"/>
      <!-- nullability -->
      <xs:element name="nullable" type="xs:boolean"/>
      <!-- unique, references, check column constraints 
           are stored as table constraints -->
      <!-- description of the column\'s meaning and content -->
      <xs:element name="description" type="xs:string" minOccurs="0"/>
    </xs:sequence>
  </xs:complexType>
  
  <!-- complex type primaryKey -->
  <xs:complexType name="primaryKeyType">
    <xs:annotation>
      <xs:documentation>
        primaryKey element in siardArchive
      </xs:documentation>
    </xs:annotation>
    <xs:sequence>
      <!-- database name of the primary key -->
      <xs:element name="name" type="xs:string" minOccurs="0" />
       <!-- description of the primary key\'s meaning and content -->
       <xs:element name="description" type="xs:string" minOccurs="0"/>
      <!-- columns belonging to the primary key -->
      <xs:element name="column" type="xs:string"  minOccurs="1" maxOccurs="unbounded"/>
    </xs:sequence>
  </xs:complexType>

  <!-- complex type foreignKeys -->
  <xs:complexType name="foreignKeysType">
    <xs:annotation>
      <xs:documentation>
        List of foreign key constraints
      </xs:documentation>
    </xs:annotation>
    <xs:sequence>
      <xs:element name="foreignKey" type="foreignKeyType" minOccurs="1" maxOccurs="unbounded" />
    </xs:sequence>
  </xs:complexType>

  <!-- complex type foreignKey -->
  <xs:complexType name="foreignKeyType">
    <xs:annotation>
      <xs:documentation>
        foreignKey element in siardArchive
      </xs:documentation>
    </xs:annotation>
    <xs:sequence>
      <!-- database name of the foreign key -->
      <xs:element name="name" type="xs:string" />
      <!--  referenced schema -->
      <xs:element name="referencedSchema" type="xs:string"/>
      <!-- referenced table -->
      <xs:element name="referencedTable" type="xs:string"/>
      <!--  references -->
      <xs:element name="reference" type="referenceType" minOccurs="1" maxOccurs="unbounded"/> 
      <!-- match type (FULL, PARTIAL, SIMPLE) -->
      <xs:element name="matchType" type="matchTypeType" minOccurs="0"/>
      <!-- ON DELETE action e.g. ON DELETE CASCADE -->
      <xs:element name="deleteAction" type="xs:string" minOccurs="0"/>
      <!-- ON UPDATE action e.g. ON UPDATE SET DEFAULT -->
      <xs:element name="updateAction" type="xs:string" minOccurs="0"/>
      <!-- description of the foreign key\'s meaning and content -->
      <xs:element name="description" type="xs:string" minOccurs="0"/>
    </xs:sequence>
  </xs:complexType>

  <!-- complex type reference -->
  <xs:complexType name="referenceType">
    <xs:annotation>
      <xs:documentation>
        reference element in siardArchive
      </xs:documentation>
    </xs:annotation>
    <xs:sequence>
      <!-- referencing column -->
      <xs:element name="column" type="xs:string"/>
      <!-- referenced column (table.column) -->
      <xs:element name="referenced" type="xs:string"/>
    </xs:sequence>
  </xs:complexType>

  <!-- complex type candidateKeys -->
  <xs:complexType name="candidateKeysType">
    <xs:annotation>
      <xs:documentation>
        List of candidate key (unique) constraints
      </xs:documentation>
    </xs:annotation>
    <xs:sequence>
      <xs:element name="candidateKey" type="candidateKeyType" minOccurs="1" maxOccurs="unbounded" />
    </xs:sequence>
  </xs:complexType>

  <!-- complex type candidateKey -->
  <xs:complexType name="candidateKeyType">
    <xs:annotation>
      <xs:documentation>
        candiate key (unique) element in siardArchive
      </xs:documentation>
    </xs:annotation>
    <xs:sequence>
      <!-- database name of the candidate key -->
      <xs:element name="name" type="xs:string"/>
       <!-- description of the candidate key\'s meaning and content -->
       <xs:element name="description" type="xs:string" minOccurs="0"/>
      <!-- columns belonging to the candidate key -->
      <xs:element name="column" type="xs:string"  minOccurs="1" maxOccurs="unbounded"/>
    </xs:sequence>
  </xs:complexType>

  <!-- complex type check constraints -->
  <xs:complexType name="checkConstraintsType">
    <xs:annotation>
      <xs:documentation>
        List of check constraints
      </xs:documentation>
    </xs:annotation>
    <xs:sequence>
      <xs:element name="checkConstraint" type="checkConstraintType" minOccurs="1" maxOccurs="unbounded" />
    </xs:sequence>
  </xs:complexType>

  <!-- complex type check constraint -->
  <xs:complexType name="checkConstraintType">
    <xs:annotation>
      <xs:documentation>
        Check constraint element in siardArchive
      </xs:documentation>
    </xs:annotation>
    <xs:sequence>
      <!-- database name of the constraint -->
      <xs:element name="name" type="xs:string"/>
      <!-- check condition -->
      <xs:element name="condition" type="xs:string"/>
      <!-- description of the constraint\'s meaning and content -->
      <xs:element name="description" type="xs:string" minOccurs="0"/>
    </xs:sequence>
  </xs:complexType>

  <!-- complex type triggers -->
  <xs:complexType name="triggersType">
    <xs:annotation>
      <xs:documentation>
        List of triggers
      </xs:documentation>
    </xs:annotation>
    <xs:sequence>
      <xs:element name="trigger" type="triggerType" minOccurs="1" maxOccurs="unbounded" />
    </xs:sequence>
  </xs:complexType>
  
  <!-- complex type trigger -->
  <xs:complexType name="triggerType">
    <xs:annotation>
      <xs:documentation>
        Trigger element in siardArchive
      </xs:documentation>
    </xs:annotation>
    <xs:sequence>
      <!-- database name of the trigger -->
      <xs:element name="name" type="xs:string" />
      <!--  action time -->
      <xs:element name="actionTime" type="actionTimeType"/>
      <!--  trigger event INSERT, DELETE, UPDATE [OF <trigger column list>] -->
      <xs:element name="triggerEvent" type="xs:string"/>
      <!--  alias list <old or new values alias> -->
      <xs:element name="aliasList" type="xs:string" minOccurs="0"/>
      <!--  triggered action -->
      <xs:element name="triggeredAction" type="xs:string"/>
      <!-- description of the trigger\'s meaning and content -->
      <xs:element name="description" type="xs:string" minOccurs="0"/>
    </xs:sequence>
  </xs:complexType>
  
  <!-- complex type routines -->
  <xs:complexType name="routinesType">
    <xs:annotation>
      <xs:documentation>
        List of routines
      </xs:documentation>
    </xs:annotation>
    <xs:sequence>
      <xs:element name="routine" type="routineType" minOccurs="1" maxOccurs="unbounded" />
    </xs:sequence>
  </xs:complexType>

  <!-- complex type routine -->
  <xs:complexType name="routineType">
    <xs:annotation>
      <xs:documentation>
        Routine
      </xs:documentation>
    </xs:annotation>
    <xs:sequence>
      <!-- database name of routine in schema -->
      <xs:element name="name" type="xs:string"/>
      <!-- description of the routines\'s meaning and content -->
      <xs:element name="description" type="xs:string" minOccurs="0"/>
      <!-- original source code (VBA, PL/SQL, ...) defining the routine -->
      <xs:element name="source" type="xs:string" minOccurs="0"/>
      <!--  SQL:1999 body of routine  -->
      <xs:element name="body" type="xs:string" minOccurs="0"/>
      <!--  routine characteristic -->
      <xs:element name="characteristic" type="xs:string" minOccurs="0"/>
      <!--  SQL:1999 data type of the return value (for functions) -->
      <xs:element name="returnType" type="xs:string" minOccurs="0"/>
      <!--  list of parameters -->
      <xs:element name="parameters" type="parametersType" minOccurs="0"/>
    </xs:sequence>
  </xs:complexType>
  
  <!-- complex type parameters -->
  <xs:complexType name="parametersType">
    <xs:annotation>
      <xs:documentation>
        List of parameters of a routine
      </xs:documentation>
    </xs:annotation>
    <xs:sequence>
      <xs:element name="parameter" type="parameterType" minOccurs="1" maxOccurs="unbounded" />
    </xs:sequence>
  </xs:complexType>
  
  <!-- complex type parameter -->
  <xs:complexType name="parameterType">
    <xs:annotation>
      <xs:documentation>
        Parameter of a routine
      </xs:documentation>
    </xs:annotation>
    <xs:sequence>
      <!--  name of parameter  -->
	  <xs:element name="name" type="xs:string"/>
	  <!--  mode of parameter (IN, OUT, INOUT) -->
	  <xs:element name="mode" type="xs:string"/>
	  <!--  SQL:1999 type of argument -->
	  <xs:element name="type" type="xs:string"/>
      <!-- original data type of the argument -->
      <xs:element name="typeOriginal" type="xs:string" minOccurs="0"/>
      <!-- description of the parameter\'s meaning and content -->
      <xs:element name="description" type="xs:string" minOccurs="0"/>
    </xs:sequence>
  </xs:complexType>

  <!-- complex type users -->
  <xs:complexType name="usersType">
    <xs:annotation>
      <xs:documentation>
        List of users
      </xs:documentation>
    </xs:annotation>
    <xs:sequence>
      <xs:element name="user" type="userType" minOccurs="1" maxOccurs="unbounded" />
    </xs:sequence>
  </xs:complexType>

  <!-- complex type user -->
  <xs:complexType name="userType">
    <xs:annotation>
      <xs:documentation>
        User
      </xs:documentation>
    </xs:annotation>
    <xs:sequence>
      <!-- user name -->
      <xs:element name="name" type="xs:string"/>
      <!-- description of the user\'s meaning and content -->
      <xs:element name="description" type="xs:string" minOccurs="0"/>
    </xs:sequence>
  </xs:complexType>

  <!-- complex type roles -->
  <xs:complexType name="rolesType">
    <xs:annotation>
      <xs:documentation>
        List of roles
      </xs:documentation>
    </xs:annotation>
    <xs:sequence>
      <xs:element name="role" type="roleType" minOccurs="1" maxOccurs="unbounded" />
    </xs:sequence>
  </xs:complexType>

  <!-- complex type role -->
  <xs:complexType name="roleType">
    <xs:annotation>
      <xs:documentation>
        Role
      </xs:documentation>
    </xs:annotation>
    <xs:sequence>
      <!-- role name -->
      <xs:element name="name" type="xs:string"/>
      <!-- role ADMIN (user or role) -->
      <xs:element name="admin" type="xs:string"/>
      <!-- description of the role\'s meaning and content -->
      <xs:element name="description" type="xs:string" minOccurs="0"/>
    </xs:sequence>
  </xs:complexType>

  <!-- complex type privileges -->
  <xs:complexType name="privilegesType">
    <xs:annotation>
      <xs:documentation>
        List of grants
      </xs:documentation>
    </xs:annotation>
    <xs:sequence>
      <xs:element name="privilege" type="privilegeType" minOccurs="1" maxOccurs="unbounded" />
    </xs:sequence>
  </xs:complexType>

  <!-- complex type privilege -->
  <xs:complexType name="privilegeType">
    <xs:annotation>
      <xs:documentation>
        Grant (incl. grant of role)
      </xs:documentation>
    </xs:annotation>
    <xs:sequence>
      <!-- privilege type (incl. ROLE privilege or "ALL PRIVILEGES" -->
      <xs:element name="type" type="xs:string"/>
      <!-- privilege object (may be omitted for ROLE privilege) -->
      <xs:element name="object" type="xs:string" minOccurs="0"/>
      <!-- GRANTED BY -->
      <xs:element name="grantor" type="xs:string"/>
      <!-- user list of users or roles or single value "PUBLIC" -->
      <xs:element name="grantee" type="xs:string"/>
      <!-- optional option "GRANT" or "ADMIN" -->
      <xs:element name="option" type="privOptionType" minOccurs="0"/>
      <!-- description of the grant\'s meaning and content -->
      <xs:element name="description" type="xs:string" minOccurs="0"/>
    </xs:sequence>
  </xs:complexType>

  <!-- simple type for digest string -->
  <xs:simpleType name="digestType">
    <xs:annotation>
      <xs:documentation>
        digestType must be empty or prefixed by MD5 oder SHA1
      </xs:documentation>
    </xs:annotation>
    <xs:restriction base="xs:string">
      <xs:whiteSpace value="collapse"/>
      <xs:pattern value="(|(MD5|SHA-1).*)" />
    </xs:restriction>
  </xs:simpleType>

  <!-- simple type for version number -->
  <xs:simpleType name="versionType">
    <xs:annotation>
      <xs:documentation>
        versionType must be constrained to "1.0"
        for conformity with this XLM schema
      </xs:documentation>
    </xs:annotation>
    <xs:restriction base="xs:string">
      <xs:whiteSpace value="collapse"/>
      <xs:enumeration value="1.0"/>
    </xs:restriction>
  </xs:simpleType>

  <!-- simple type for privilege option -->
  <xs:simpleType name="privOptionType">
    <xs:annotation>
      <xs:documentation>
        privOptionType must be "ADMIN" or "GRANT"
      </xs:documentation>
    </xs:annotation>
    <xs:restriction base="xs:string">
      <xs:whiteSpace value="collapse"/>
      <xs:enumeration value="ADMIN"/>
      <xs:enumeration value="GRANT"/>
    </xs:restriction>
  </xs:simpleType>

  <!-- simple type for mandatory string 
       which must contain at least 1 character -->
  <xs:simpleType name="mandatoryString">
    <xs:annotation>
      <xs:documentation>
        mandatoryType must contain at least 1 character
      </xs:documentation>
    </xs:annotation>
    <xs:restriction base="xs:string">
      <xs:whiteSpace value="preserve"/>
      <xs:minLength value="1" />
    </xs:restriction>
  </xs:simpleType>
  
  <!-- simple type of a filesystem (file or folder) name -->
  <xs:simpleType name="fsName">
    <xs:annotation>
      <xs:documentation>
        fsNames may only consist of ASCII characters and digits
        and must start with a non-digit 
      </xs:documentation>
    </xs:annotation>
    <xs:restriction base="xs:string">
      <xs:pattern value="([a-z]|[A-Z])([a-z]|[A-Z]|[0-9]).*" />
      <xs:minLength value="1" />
    </xs:restriction>
  </xs:simpleType>
  
  <!-- simple type for action time of a trigger -->
  <xs:simpleType name="actionTimeType">
    <xs:annotation>
      <xs:documentation>
        actionTime is BEFORE or AFTER
      </xs:documentation>
    </xs:annotation>
    <xs:restriction base="xs:string">
      <xs:enumeration value="BEFORE" />
      <xs:enumeration value="AFTER" />
    </xs:restriction>
  </xs:simpleType>
  
  <!-- simple type for match type of a foreign key -->
  <xs:simpleType name="matchTypeType">
    <xs:annotation>
      <xs:documentation>
        matchType is FULL, PARTIAL or SIMPLE
      </xs:documentation>
    </xs:annotation>
    <xs:restriction base="xs:string">
      <xs:enumeration value="FULL" />
      <xs:enumeration value="PARTIAL" />
      <xs:enumeration value="SIMPLE" />
    </xs:restriction>
  </xs:simpleType>

</xs:schema>
';

// -----------------------------------------------------------------------------
$static_siard2html = '<?xml version="1.0" encoding="iso-8859-1"?>
<!--
=== SiardMetaToXhtml.xsl ===============================================
Main script.
Version     : $Id: c2schema.php 190 2013-06-11 13:08:30Z U80789367 $
Application : Swiss Federal Archive SIARD v2.x
Description : XS transformation to transform metadata xml to xhtml.
Platform    : Xsl transformer. Implemented and tested with Xalan.
========================================================================
Copyright  : Swiss Federal Archives, Berne, Switzerland, 2008
Created    : 16.06.2008, Niklaus Aeschbacher, Enter AG, Zurich
========================================================================
-->
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
  xmlns:siard="http://www.bar.admin.ch/xmlns/siard/1.0/metadata.xsd"
  xmlns:html="http://www.w3.org/1999/xhtml"
  xmlns="http://www.w3.org/1999/xhtml" exclude-result-prefixes="html"
  version="2.0">
  
  <xsl:variable name="quote">
    "
  </xsl:variable>
  <xsl:variable name="at">
    @
  </xsl:variable>
  <xsl:output method="xml" indent="yes" encoding="iso-8859-1"
    doctype-system="http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"
    doctype-public="-//W3C//DTD XHTML 1.0 Transitional//EN"/>
  
  <xsl:template match="text()" name="remove-quotes">
    <xsl:param name="input" select="."/>
    
    <xsl:choose>
      <xsl:when test="contains($input,$quote)">
        <xsl:call-template name="remove-quotes">
          <xsl:with-param name="input"
            select="concat(substring-before($input,$quote),\'\',substring-after($input,$quote))"/>
        </xsl:call-template>
      </xsl:when>
      <xsl:otherwise>
        <xsl:value-of select="$input"/>
      </xsl:otherwise>
    </xsl:choose>
  </xsl:template>
  
  <xsl:template match="text()" name="remove-ats">
    <xsl:param name="input" select="."/>
    
    <xsl:choose>
      <xsl:when test="contains($input,$at)">
        <xsl:call-template name="remove-ats">
          <xsl:with-param name="input"
            select="concat(substring-before($input,$at),\'\',substring-after($input,$at))"/>
        </xsl:call-template>
      </xsl:when>
      <xsl:otherwise>
        <xsl:value-of select="$input"/>
      </xsl:otherwise>
    </xsl:choose>
  </xsl:template>
  
  <xsl:template match="/siard:siardArchive">
    <html xmlns="http://www.w3.org/1999/xhtml">
      <head>
        <title>
          SIARD File Content
        </title>
        <style type="text/css">
          <!-- Common table  styles -->
          
          .tableTitle { background-color: #999999; color: #FFFFFF;
          text-align: center;
          
          }
          
          .tableTitleDark { text-align: center; font-weight: bold;
          
          }
          
          .horizontalTitleColumn { text-align: left; }
          
          table.light { border-width: 1px;
          
          border-style: none; border-color: gray; border-collapse: separate;
          background-color: white; } table.light th { border-width: 1px;
          padding: 1px; border-style: ridge; border-color: white;
          background-color: #AFEEEE;
          
          } table.light td { border-width: 1px; padding: 1px; vertical-align:
          text-top; border-style: ridge; border-color: white;
          background-color: #F0F8FF;
          
          }
          
          table.strong { border-width: 1px;
          
          border-style: none; border-color: gray; border-collapse: separate;
          background-color: white; } table.strong th { border-width: 1px;
          padding: 1px; border-style: ridge; border-color: white;
          background-color: #9999FF;
          
          }
          
          table.strong td { vertical-align: text-top; border-width: 1px;
          padding: 1px; border-style: ridge; border-color: white;
          background-color: #99CCFF;
          
          }
          
          table.medium { border-width: 1px;
          
          border-style: none; border-color: gray; border-collapse: separate;
          background-color: white; }
          
          table.medium th { border-width: 1px; padding: 1px; border-style:
          ridge; border-color: white; background-color: #FFE4E1;
          
          }
          
          table.medium td { border-width: 1px; padding: 1px; vertical-align:
          text-top; border-style: ridge; border-color: white;
          background-color: #FFF0F5;
          
          }
          
          table.title { border-width: 1px;
          
          border-style: none; border-color: gray; border-collapse: separate;
          background-color: white; }
          
          table.title th { border-width: 1px; padding: 1px; border-style:
          ridge; border-color: white; background-color: #F5F5F5;
          
          }
          
          table.title td { border-width: 1px; padding: 1px; vertical-align:
          text-top; border-style: ridge; border-color: white;
          background-color: #F8F8FF;
          
          }
          
          <!-- Data base description styles -->
          .databaseTitleColumn { text-align: right; } .databaseValueColumn {
          text-align: left; }
          
          <!-- Schema description styles -->
          .schemaTitleColumn { text-align: right; } .schemaValueColumn {
          text-align: left; }
          
          body.common { font-family: Verdana,Arial, Helvetica, sans-serif;
          font-size: 8pt; color: #555555; }
          
          h1.small { font-size: 14pt; }
          
          h2.small { font-size: 10pt; }
          
          h3.small { font-size: 8pt; }
          
        </style>
      </head>
      <body class="common">
        <h1 class="small">
          SIARD File Content
        </h1>
        <h2 class="small">
          <xsl:value-of select="siard:dbname"/>
        </h2>
        <table class="title">
          <tr>
            <td colspan="2" class="tableTitle">
              DATA BASE
            </td>
          </tr>
          <tr>
            <th class="databaseTitleColumn">
              <xsl:text>
                Name
              </xsl:text>
            </th>
            <th class="databaseValueColumn">
              <xsl:value-of select="siard:dbname"/>
            </th>
          </tr>
          <tr>
            <td class="databaseTitleColumn">
              <xsl:text>
                Version
              </xsl:text>
            </td>
            <td class="databaseValueColumn">
              <xsl:value-of select="@version"/>
            </td>
          </tr>
          <tr>
            <td class="databaseTitleColumn">
              <xsl:text>
                Description
              </xsl:text>
            </td>
            <td class="databaseValueColumn">
              <xsl:value-of select="siard:description"/>
            </td>
          </tr>
          <tr>
            <td class="databaseTitleColumn">
              <xsl:text>
                Archiver
              </xsl:text>
            </td>
            <td class="databaseValueColumn">
              <xsl:value-of select="siard:archiver"/>
            </td>
          </tr>
          <tr>
            <td class="databaseTitleColumn">
              <xsl:text>
                Archiver Contact
              </xsl:text>
            </td>
            <td class="databaseValueColumn">
              <xsl:value-of select="siard:archiverContact"/>
            </td>
          </tr>
          <tr>
            <td class="databaseTitleColumn">
              <xsl:text>
                Data owner
              </xsl:text>
            </td>
            <td class="databaseValueColumn">
              <xsl:value-of select="siard:dataOwner"/>
            </td>
          </tr>
          <tr>
            <td class="databaseTitleColumn">
              <xsl:text>
                Data origin timespan
              </xsl:text>
            </td>
            <td class="databaseValueColumn">
              <xsl:value-of select="siard:dataOriginTimespan"/>
            </td>
          </tr>
          <tr>
            <td class="databaseTitleColumn">
              <xsl:text>
                Archival date
              </xsl:text>
            </td>
            <td class="databaseValueColumn">
              <xsl:value-of select="siard:archivalDate"/>
            </td>
          </tr>
          <tr>
            <td class="databaseTitleColumn">
              <xsl:text>
                Message digest
              </xsl:text>
            </td>
            <td class="databaseValueColumn">
              <xsl:value-of select="siard:messageDigest"/>
            </td>
          </tr>
          <tr>
            <td class="databaseTitleColumn">
              <xsl:text>
                Client machine
              </xsl:text>
            </td>
            <td class="databaseValueColumn">
              <xsl:value-of select="siard:clientMachine"/>
            </td>
          </tr>
          <tr>
            <td class="databaseTitleColumn">
              <xsl:text>
                Producing application
              </xsl:text>
            </td>
            <td class="databaseValueColumn">
              <xsl:value-of select="siard:producingApplication"/>
            </td>
          </tr>
          <tr>
            <td class="databaseTitleColumn">
              <xsl:text>
                Database product
              </xsl:text>
            </td>
            <td class="databaseValueColumn">
              <xsl:value-of select="siard:databaseProduct"/>
            </td>
          </tr>
          <tr>
            <td class="databaseTitleColumn">
              <xsl:text>
                Connection
              </xsl:text>
            </td>
            <td class="databaseValueColumn">
              <xsl:value-of select="siard:Connection"/>
            </td>
          </tr>
          <tr>
            <td class="databaseTitleColumn">
              <xsl:text>
                Data base user
              </xsl:text>
            </td>
            <td class="databaseValueColumn">
              <xsl:value-of select="siard:databaseUser"/>
            </td>
          </tr>
        </table>
        
        <!-- Table of contents -->
        <h2>
          <xsl:text>
            Table of contents
          </xsl:text>
        </h2>
        <xsl:if test="count(siard:schemas/siard:schema) &gt; 0">
          <ul>
            <li>
              <h2 class="small">
                Schemas
              </h2>
              <xsl:for-each select="siard:schemas/siard:schema">
                <p/>
                
                <!--  <xsl:variable name="schemaAName" select=\'replace(siard:name, "\\W","")\'/> -->
                <xsl:variable name="schemaAName">
                  <xsl:call-template name="remove-quotes">
                    <xsl:with-param name="input" select="siard:name"/>
                  </xsl:call-template>
                </xsl:variable>
                <xsl:variable name="schemaName">
                  <xsl:call-template name="remove-quotes">
                    <xsl:with-param name="input" select="siard:name"/>
                  </xsl:call-template>
                </xsl:variable>
                <ul>
                  <li>
                    <a href="#{$schemaAName}">
                      <xsl:value-of select="$schemaName"/>
                    </a>
                    <p/>
                    <xsl:if test="count(siard:tables/siard:table) &gt; 0">
                      <ul>
                        <li>
                          <h3 class="small">
                            Tables
                          </h3>
                          <xsl:for-each select="siard:tables/siard:table">
                            <p/>
                            
                            <xsl:variable name="tableAName">
                              <xsl:call-template name="remove-quotes">
                                <xsl:with-param name="input" select="siard:name"/>
                              </xsl:call-template>
                            </xsl:variable>
                            <xsl:variable name="tableName">
                              <xsl:call-template name="remove-quotes">
                                <xsl:with-param name="input" select="siard:name"/>
                              </xsl:call-template>
                            </xsl:variable>
                            
                            <a href="#{$schemaAName}.{$tableAName}">
                              <xsl:value-of select="$tableName"/>
                            </a>
                            <p/>
                          </xsl:for-each>
                        </li>
                      </ul>
                    </xsl:if>
                    
                    <xsl:if test="count(siard:views/siard:view) &gt; 0">
                      <ul>
                        <li>
                          <h3 class="small">
                            Views
                          </h3>
                          <xsl:for-each select="siard:views/siard:view">
                            <p/>
                            <xsl:variable name="viewAName">
                              <xsl:call-template name="remove-quotes">
                                <xsl:with-param name="input" select="siard:name"/>
                              </xsl:call-template>
                            </xsl:variable>
                            <xsl:variable name="viewName" select="siard:name"/>
                            <a href="#{$schemaAName}.{$viewAName}">
                              <xsl:value-of select="$viewName"/>
                            </a>
                            <p/>
                          </xsl:for-each>
                        </li>
                      </ul>
                    </xsl:if>
                    
                    <xsl:if test="count(siard:routines/siard:routine) &gt; 0">
                      <ul>
                        <li>
                          <h3 class="small">
                            Routines
                          </h3>
                          <xsl:for-each select="siard:routines/siard:routine">
                            <p/>
                            <xsl:variable name="routineAName">
                              <xsl:call-template name="remove-quotes">
                                <xsl:with-param name="input" select="siard:name"/>
                              </xsl:call-template>
                            </xsl:variable>
                            <xsl:variable name="routineName" select="siard:name"/>
                            <a href="#{$schemaAName}.{$routineAName}">
                              <xsl:value-of select="$routineName"/>
                            </a>
                          </xsl:for-each>
                        </li>
                      </ul>
                    </xsl:if>
                  </li>
                </ul>
              </xsl:for-each>
            </li>
          </ul>
        </xsl:if>
        <ul>
          <li>
            <h2 class="small">
              <a href="#users">
                Users
              </a>
            </h2>
          </li>
          <li>
            <h2 class="small">
              <a href="#roles">
                Roles
              </a>
            </h2>
          </li>
          <li>
            <h2 class="small">
              <a href="#privileges">
                Privileges
              </a>
            </h2>
          </li>
        </ul>
        
        <!-- The content -->
        <h2>
          Contents
        </h2>
        <xsl:if test="count(siard:schemas/siard:schema) &gt; 0">
          <ul>
            <li>
              <h2 class="small">
                Schemas
              </h2>
              <xsl:for-each select="siard:schemas/siard:schema">
                <p/>
                <!-- <xsl:variable name="schemaName" select="siard:name"/> -->
                <xsl:variable name="schemaAName">
                  <xsl:call-template name="remove-quotes">
                    <xsl:with-param name="input" select="siard:name"/>
                  </xsl:call-template>
                </xsl:variable>
                <xsl:variable name="schemaName" select="siard:name"/>
                <ul>
                  <li>
                    <table class="strong" width="100%">
                      <tr>
                        <td colspan="2" class="tableTitleDark">
                          SCHEMA
                          <a name="{$schemaAName}"/>
                        </td>
                      </tr>
                    </table>
                    <table class="strong">
                      <tr>
                        <th class="horizontalTitleColumn">
                          Name
                        </th>
                        <th class="horizontalTitleColumn">
                          Folder
                        </th>
                      </tr>
                      <tr>
                        <td class="schemaValueColumn">
                          <xsl:value-of select="$schemaName"/>
                        </td>
                        <td class="schemaValueColumn">
                          <xsl:value-of select="siard:folder"/>
                        </td>
                      </tr>
                    </table>
                    <p/>
                    <xsl:if test="count(siard:tables/siard:table) &gt; 0">
                      <ul>
                        <li>
                          <h3 class="small">
                            Tables
                          </h3>
                          <xsl:for-each select="siard:tables/siard:table">
                            <p/>
                            <xsl:variable name="tableAName">
                              <xsl:call-template name="remove-quotes">
                                <xsl:with-param name="input" select="siard:name"/>
                              </xsl:call-template>
                            </xsl:variable>
                            <xsl:variable name="tableName" select="siard:name"/>
                            <table class="medium">
                              <tr>
                                <td colspan="4" class="tableTitleDark">
                                  TABLE
                                  <a name="{$schemaAName}.{$tableAName}"/>
                                </td>
                              </tr>
                              <tr>
                                <th class="horizontalTitleColumn">
                                  Name
                                </th>
                                <th class="horizontalTitleColumn">
                                  Folder
                                </th>
                                <th class="horizontalTitleColumn">
                                  Rows
                                </th>
                                <th class="horizontalTitleColumn">
                                  Description
                                </th>
                              </tr>
                              <tr>
                                <td class="schemaValueColumn">
                                  <xsl:value-of select="$tableName"/>
                                </td>
                                <td class="schemaValueColumn">
                                  <xsl:value-of select="siard:folder"/>
                                </td>
                                <td class="schemaValueColumn">
                                  <xsl:value-of select="siard:rows"/>
                                </td>
                                <td class="schemaValueColumn">
                                  <xsl:value-of select="siard:description"/>
                                </td>
                              </tr>
                            </table>
                            <p/>
                            <xsl:if test="count(siard:columns/siard:column) &gt; 0">
                              <ul>
                                <li>
                                  <h3 class="small">
                                    Columns
                                  </h3>
                                  <table class="light">
                                    <tr>
                                      <th class="horizontalTitleColumn">
                                        Name
                                      </th>
                                      <th class="horizontalTitleColumn">
                                        Folder
                                      </th>
                                      <th class="horizontalTitleColumn">
                                        Type
                                      </th>
                                      <th class="horizontalTitleColumn">
                                        Original type
                                      </th>
                                      <th class="horizontalTitleColumn">
                                        Default value
                                      </th>
                                      <th class="horizontalTitleColumn">
                                        Nullable
                                      </th>
                                      <th class="horizontalTitleColumn">
                                        Description
                                      </th>
                                    </tr>
                                    <xsl:for-each select="siard:columns/siard:column">
                                      <tr>
                                        <td class="schemaValueColumn">
                                          <xsl:variable name="columnAName">
                                            <xsl:call-template name="remove-quotes">
                                              <xsl:with-param name="input" select="siard:name"/>
                                            </xsl:call-template>
                                          </xsl:variable>
                                          <xsl:variable name="columnName"
                                            select="siard:name"/>
                                          <xsl:value-of select="$columnName"/>
                                          
                                          <a
                                            name="{$schemaAName}.{$tableAName}.{$columnAName}"/>
                                        </td>
                                        <td class="schemaValueColumn">
                                          <xsl:value-of select="siard:folder"/>
                                        </td>
                                        <td class="schemaValueColumn">
                                          <xsl:value-of select="siard:type"/>
                                        </td>
                                        <td class="schemaValueColumn">
                                          <xsl:value-of select="siard:typeOriginal"/>
                                        </td>
                                        <td class="schemaValueColumn">
                                          <xsl:value-of select="siard:defaultValue"/>
                                        </td>
                                        <td class="schemaValueColumn">
                                          <xsl:value-of select="siard:nullable"/>
                                        </td>
                                        <td class="schemaValueColumn">
                                          <xsl:value-of select="siard:description"/>
                                        </td>
                                      </tr>
                                    </xsl:for-each>
                                  </table>
                                </li>
                              </ul>
                            </xsl:if>
                            <p/>
                            <xsl:if test="count(siard:primaryKey) &gt; 0">
                              <ul>
                                <li>
                                  <h3 class="small">
                                    Primary key
                                  </h3>
                                  <table class="light">
                                    <tr>
                                      <th class="horizontalTitleColumn">
                                        Name
                                      </th>
                                      <th class="horizontalTitleColumn">
                                        Column(s)
                                      </th>
                                      <th class="horizontalTitleColumn">
                                        Description
                                      </th>
                                    </tr>
                                    <tr>
                                      <td class="schemaValueColumn">
                                        <xsl:variable name="primaryKey"
                                          select="siard:primaryKey/siard:name"/>
                                        <xsl:variable name="primaryKeyAName">
                                          <xsl:call-template name="remove-quotes">
                                            <xsl:with-param name="input"
                                              select="siard:primaryKey/siard:name"/>
                                          </xsl:call-template>
                                        </xsl:variable>
                                        <a
                                          name="{$schemaAName}.{$tableAName}.{$primaryKeyAName}"/>
                                        <xsl:value-of select="$primaryKey"/>
                                      </td>
                                      <td class="schemaValueColumn">
                                        <xsl:for-each
                                          select="siard:primaryKey/siard:column">
                                          <xsl:variable name="columnAName">
                                            <xsl:call-template name="remove-quotes">
                                              <xsl:with-param name="input" select="."/>
                                            </xsl:call-template>
                                          </xsl:variable>
                                          <xsl:variable name="columnName" select="."/>
                                          <a
                                            href="#{$schemaAName}.{$tableAName}.{$columnAName}">
                                            <xsl:value-of select="$columnName"/>
                                          </a>
                                          <br/>
                                        </xsl:for-each>
                                      </td>
                                      <td class="schemaValueColumn">
                                        <xsl:value-of
                                          select="siard:primaryKey/siard:description"/>
                                      </td>
                                    </tr>
                                  </table>
                                </li>
                              </ul>
                            </xsl:if>
                            <p/>
                            <xsl:if
                              test="count(siard:foreignKeys/siard:foreignKey) &gt; 0">
                              <ul>
                                <li>
                                  <h3 class="small">
                                    Foreign keys
                                  </h3>
                                  <table class="light">
                                    <tr>
                                      <th class="horizontalTitleColumn">
                                        Name
                                      </th>
                                      <th class="horizontalTitleColumn">
                                        Referenced schema
                                      </th>
                                      <th class="horizontalTitleColumn">
                                        Referenced table
                                      </th>
                                      <th class="horizontalTitleColumn">
                                        Reference(s) (Column, Referenced)
                                      </th>
                                      <th class="horizontalTitleColumn">
                                        Match type
                                      </th>
                                      <th class="horizontalTitleColumn">
                                        Delete action
                                      </th>
                                      <th class="horizontalTitleColumn">
                                        Update action
                                      </th>
                                      <th class="horizontalTitleColumn">
                                        Description
                                      </th>
                                    </tr>
                                    <xsl:for-each
                                      select="siard:foreignKeys/siard:foreignKey">
                                      <xsl:variable name="referencedTableAName">
                                        <xsl:call-template name="remove-quotes">
                                          <xsl:with-param name="input"
                                            select="siard:referencedTable"/>
                                        </xsl:call-template>
                                      </xsl:variable>
                                      <xsl:variable name="referencedTable"
                                        select="siard:referencedTable"/>
                                      <xsl:variable name="referencedSchemaAName">
                                        <xsl:call-template name="remove-quotes">
                                          <xsl:with-param name="input"
                                            select="siard:referencedSchema"/>
                                        </xsl:call-template>
                                      </xsl:variable>
                                      <xsl:variable name="referencedSchema"
                                        select="siard:referencedSchema"/>
                                      <xsl:variable name="foreignKeyAName">
                                        <xsl:call-template name="remove-quotes">
                                          <xsl:with-param name="input" select="siard:name"/>
                                        </xsl:call-template>
                                      </xsl:variable>
                                      
                                      <xsl:variable name="foreignKey" select="siard:name"/>
                                      <tr>
                                        <td class="schemaValueColumn">
                                          <a
                                            name="{$schemaAName}.{$tableAName}.{$foreignKeyAName}"/>
                                          <xsl:value-of select="$foreignKey"/>
                                        </td>
                                        <td class="schemaValueColumn">
                                          <a href="#{$referencedSchemaAName}">
                                            <xsl:value-of select="$referencedSchema"/>
                                          </a>
                                        </td>
                                        <td class="schemaValueColumn">
                                          <a
                                            href="#{$referencedSchemaAName}.{$referencedTableAName}">
                                            <xsl:value-of select="$referencedTable"/>
                                          </a>
                                        </td>
                                        <td class="schemaValueColumn">
                                          
                                          <xsl:for-each select="siard:reference">
                                            <xsl:variable name="columnAName">
                                              <xsl:call-template name="remove-quotes">
                                                <xsl:with-param name="input"
                                                  select="siard:column"/>
                                              </xsl:call-template>
                                            </xsl:variable>
                                            <xsl:variable name="columnName"
                                              select="siard:column"/>
                                            <a
                                              href="#{$schemaAName}.{$tableAName}.{$columnAName}">
                                              <xsl:value-of select="$columnName"/>
                                            </a>
                                            ,
                                            <xsl:variable name="referencedColumnAName">
                                              <xsl:call-template name="remove-quotes">
                                                <xsl:with-param name="input"
                                                  select="siard:referenced"/>
                                              </xsl:call-template>
                                            </xsl:variable>
                                            <xsl:variable name="referencedColumn"
                                              select="siard:referenced"/>
                                            <a
                                              href="#{$referencedSchemaAName}.{$referencedTableAName}.{$referencedColumnAName}">
                                              <xsl:value-of select="$referencedColumn"/>
                                            </a>
                                            <br/>
                                          </xsl:for-each>
                                          
                                        </td>
                                        <td class="schemaValueColumn">
                                          <xsl:value-of select="siard:matchType"/>
                                        </td>
                                        <td class="schemaValueColumn">
                                          <xsl:value-of select="siard:deleteAction"/>
                                        </td>
                                        <td class="schemaValueColumn">
                                          <xsl:value-of select="siard:updateAction"/>
                                        </td>
                                        <td class="schemaValueColumn">
                                          <xsl:value-of select="siard:description"/>
                                        </td>
                                      </tr>
                                    </xsl:for-each>
                                  </table>
                                  
                                </li>
                              </ul>
                            </xsl:if>
                            <p/>
                            <xsl:if
                              test="count(siard:candidateKeys/siard:candidateKey) &gt; 0">
                              <ul>
                                <li>
                                  <h3 class="small">
                                    Candidate keys
                                  </h3>
                                  <table class="light">
                                    <tr>
                                      <th class="horizontalTitleColumn">
                                        Name
                                      </th>
                                      <th class="horizontalTitleColumn">
                                        Column(s)
                                      </th>
                                      <th class="horizontalTitleColumn">
                                        Description
                                      </th>
                                    </tr>
                                    <xsl:for-each
                                      select="siard:candidateKeys/siard:candidateKey">
                                      <xsl:variable name="candidateKeyAName">
                                        <xsl:call-template name="remove-quotes">
                                          <xsl:with-param name="input" select="siard:name"/>
                                        </xsl:call-template>
                                      </xsl:variable>
                                      <xsl:variable name="candidateKey"
                                        select="siard:name"/>
                                      <tr>
                                        <td class="schemaValueColumn">
                                          <a
                                            name="{$schemaAName}.{$tableAName}.{$candidateKeyAName}"/>
                                          <xsl:value-of select="$candidateKey"/>
                                        </td>
                                        <td class="schemaValueColumn">
                                          <xsl:for-each select="siard:column">
                                            <xsl:variable name="columnAName">
                                              <xsl:call-template name="remove-quotes">
                                                <xsl:with-param name="input" select="."/>
                                              </xsl:call-template>
                                            </xsl:variable>
                                            <xsl:variable name="columnName" select="."/>
                                            <a
                                              href="#{$schemaAName}.{$tableAName}.{$columnAName}">
                                              <xsl:value-of select="$columnName"/>
                                            </a>
                                            <br/>
                                          </xsl:for-each>
                                          
                                        </td>
                                        <td class="schemaValueColumn">
                                          <xsl:value-of select="siard:description"/>
                                        </td>
                                      </tr>
                                    </xsl:for-each>
                                  </table>
                                  
                                </li>
                              </ul>
                            </xsl:if>
                          </xsl:for-each>
                        </li>
                      </ul>
                    </xsl:if>
                    
                    <xsl:if test="count(siard:views/siard:view) &gt; 0">
                      <ul>
                        <li>
                          <h3 class="small">
                            Views
                          </h3>
                          <xsl:for-each select="siard:views/siard:view">
                            <p/>
                            <xsl:variable name="viewAName">
                              <xsl:call-template name="remove-quotes">
                                <xsl:with-param name="input" select="siard:name"/>
                              </xsl:call-template>
                            </xsl:variable>
                            <xsl:variable name="viewName" select="siard:name"/>
                            
                            <table class="medium">
                              <tr>
                                <td colspan="4" class="tableTitleDark">
                                  VIEW
                                  <a name="{$schemaAName}.{$viewAName}"/>
                                </td>
                              </tr>
                              <tr>
                                <th class="horizontalTitleColumn">
                                  Name
                                </th>
                                <th class="horizontalTitleColumn">
                                  Query
                                </th>
                                <th class="horizontalTitleColumn">
                                  Original query
                                </th>
                                <th class="horizontalTitleColumn">
                                  Description
                                </th>
                              </tr>
                              <tr>
                                <td class="schemaValueColumn">
                                  <xsl:value-of select="$viewName"/>
                                </td>
                                <td class="schemaValueColumn">
                                  <xsl:value-of select="siard:query"/>
                                </td>
                                <td class="schemaValueColumn">
                                  <xsl:value-of select="siard:queryOriginal"/>
                                </td>
                                <td class="schemaValueColumn">
                                  <xsl:value-of select="siard:description"/>
                                </td>
                              </tr>
                            </table>
                            <p/>
                            <xsl:if test="count(siard:columns/siard:column) &gt; 0">
                              <ul>
                                <li>
                                  
                                  <h3 class="small">
                                    Columns
                                  </h3>
                                  
                                  <table class="light">
                                    <tr>
                                      <th class="horizontalTitleColumn">
                                        Name
                                      </th>
                                      <th class="horizontalTitleColumn">
                                        Folder
                                      </th>
                                      <th class="horizontalTitleColumn">
                                        Type
                                      </th>
                                      <th class="horizontalTitleColumn">
                                        Original type
                                      </th>
                                      <th class="horizontalTitleColumn">
                                        Default value
                                      </th>
                                      <th class="horizontalTitleColumn">
                                        Nullable
                                      </th>
                                      <th class="horizontalTitleColumn">
                                        Description
                                      </th>
                                    </tr>
                                    <xsl:for-each select="siard:columns/siard:column">
                                      <tr>
                                        <td class="schemaValueColumn">
                                          <xsl:variable name="columnAName">
                                            <xsl:call-template name="remove-quotes">
                                              <xsl:with-param name="input" select="siard:name"/>
                                            </xsl:call-template>
                                          </xsl:variable>
                                          <xsl:variable name="columnName"
                                            select="siard:name"/>
                                          <xsl:value-of select="$columnName"/>
                                          <a
                                            name="{$schemaAName}.{$viewAName}.{$columnAName}"/>
                                        </td>
                                        <td class="schemaValueColumn">
                                          <xsl:value-of select="siard:folder"/>
                                        </td>
                                        <td class="schemaValueColumn">
                                          <xsl:value-of select="siard:type"/>
                                        </td>
                                        <td class="schemaValueColumn">
                                          <xsl:value-of select="siard:typeOriginal"/>
                                        </td>
                                        <td class="schemaValueColumn">
                                          <xsl:value-of select="siard:defaultValue"/>
                                        </td>
                                        <td class="schemaValueColumn">
                                          <xsl:value-of select="siard:nullable"/>
                                        </td>
                                        <td class="schemaValueColumn">
                                          <xsl:value-of select="siard:description"/>
                                        </td>
                                      </tr>
                                      
                                    </xsl:for-each>
                                  </table>
                                  
                                  
                                </li>
                              </ul>
                            </xsl:if>
                          </xsl:for-each>
                        </li>
                      </ul>
                    </xsl:if>
                    
                    <xsl:if test="count(siard:routines/siard:routine) &gt; 0">
                      <ul>
                        <li>
                          <p/>
                          <h3 class="small">
                            Routines
                          </h3>
                          <xsl:for-each select="siard:routines/siard:routine">
                            <p/>
                            <xsl:variable name="routineAName">
                              <xsl:call-template name="remove-quotes">
                                <xsl:with-param name="input" select="siard:name"/>
                              </xsl:call-template>
                            </xsl:variable>
                            <xsl:variable name="routineName" select="siard:name"/>
                            
                            <table class="medium">
                              <tr>
                                <td colspan="6" class="tableTitleDark">
                                  ROUTINE
                                  <a name="{$schemaAName}.{$routineAName}"/>
                                </td>
                              </tr>
                              <tr>
                                <th class="horizontalTitleColumn">
                                  Name
                                </th>
                                <th class="horizontalTitleColumn">
                                  Description
                                </th>
                                <th class="horizontalTitleColumn">
                                  Source
                                </th>
                                <th class="horizontalTitleColumn">
                                  Body
                                </th>
                                <th class="horizontalTitleColumn">
                                  Characteristic
                                </th>
                                <th class="horizontalTitleColumn">
                                  Return type
                                </th>
                              </tr>
                              <tr>
                                <td class="schemaValueColumn">
                                  <xsl:value-of select="$routineName"/>
                                </td>
                                <td class="schemaValueColumn">
                                  <xsl:value-of select="siard:description"/>
                                </td>
                                <td class="schemaValueColumn">
                                  <xsl:value-of select="siard:source"/>
                                </td>
                                <td class="schemaValueColumn">
                                  <xsl:value-of select="siard:body"/>
                                </td>
                                <td class="schemaValueColumn">
                                  <xsl:value-of select="siard:characteristic"/>
                                </td>
                                <td class="schemaValueColumn">
                                  <xsl:value-of select="siard:returnType"/>
                                </td>
                              </tr>
                            </table>
                            <p/>
                            <xsl:if
                              test="count(siard:parameters/siard:parameter) &gt; 0">
                              <ul>
                                <li>
                                  
                                  <h3 class="small">
                                    Parameters
                                  </h3>
                                  
                                  <table class="light">
                                    <tr>
                                      <th class="horizontalTitleparameter">
                                        Name
                                      </th>
                                      <th class="horizontalTitleparameter">
                                        Mode
                                      </th>
                                      <th class="horizontalTitleparameter">
                                        Type
                                      </th>
                                      <th class="horizontalTitleparameter">
                                        Original type
                                      </th>
                                      <th class="horizontalTitleparameter">
                                        Description
                                      </th>
                                    </tr>
                                    <xsl:for-each
                                      select="siard:parameters/siard:parameter">
                                      <tr>
                                        <td class="schemaValueparameter">
                                          <xsl:variable name="parameterPreAName">
                                            <xsl:call-template name="remove-quotes">
                                              <xsl:with-param name="input" select="siard:name"/>
                                            </xsl:call-template>
                                          </xsl:variable>
                                          <xsl:variable name="parameterAName">
                                            <xsl:call-template name="remove-ats">
                                              <xsl:with-param name="input"
                                                select="$parameterPreAName"/>
                                            </xsl:call-template>
                                          </xsl:variable>
                                          
                                          <xsl:variable name="parameterName"
                                            select="siard:name"/>
                                          <xsl:value-of select="$parameterName"/>
                                          <a
                                            name="{$schemaAName}.{$routineAName}.{$parameterAName}"/>
                                        </td>
                                        <td class="schemaValueparameter">
                                          <xsl:value-of select="siard:mode"/>
                                        </td>
                                        <td class="schemaValueparameter">
                                          <xsl:value-of select="siard:type"/>
                                        </td>
                                        <td class="schemaValueparameter">
                                          <xsl:value-of select="siard:typeOriginal"/>
                                        </td>
                                        <td class="schemaValueparameter">
                                          <xsl:value-of select="siard:description"/>
                                        </td>
                                      </tr>
                                    </xsl:for-each>
                                  </table>
                                  
                                </li>
                              </ul>
                            </xsl:if>
                          </xsl:for-each>
                        </li>
                      </ul>
                    </xsl:if>
                  </li>
                </ul>
              </xsl:for-each>
            </li>
          </ul>
        </xsl:if>
        <!-- Global contents -->
        <h2>
          Schema overall contents
        </h2>
        <xsl:if test="count(siard:users/siard:user) &gt; 0">
          <ul>
            <li>
              <h2 class="small">
                Users
                <a name="users"/>
              </h2>
              
              <table class="strong">
                <tr>
                  <th class="horizontalTitleColumn">
                    Name
                  </th>
                  <th class="horizontalTitleColumn">
                    Description
                  </th>
                </tr>
                <xsl:for-each select="siard:users/siard:user">
                  <xsl:variable name="userAName">
                    <xsl:call-template name="remove-quotes">
                      <xsl:with-param name="input" select="siard:name"/>
                    </xsl:call-template>
                  </xsl:variable>
                  <xsl:variable name="userName" select="siard:name"/>
                  <tr>
                    <td class="userValueColumn">
                      <xsl:value-of select="$userName"/>
                      <a name="user.{$userAName}"/>
                    </td>
                    <td class="userValueColumn">
                      <xsl:value-of select="siard:description"/>
                    </td>
                  </tr>
                </xsl:for-each>
              </table>
              
            </li>
          </ul>
        </xsl:if>
        <xsl:if test="count(siard:roles/siard:role) &gt; 0">
          <ul>
            <li>
              <h2 class="small">
                Roles
                <a name="roles"/>
              </h2>
              
              <table class="strong">
                <tr>
                  <th class="horizontalTitleColumn">
                    Name
                  </th>
                  <th class="horizontalTitleColumn">
                    Administrator
                  </th>
                  <th class="horizontalTitleColumn">
                    Description
                  </th>
                </tr>
                <xsl:for-each select="siard:roles/siard:role">
                  <xsl:variable name="roleName" select="siard:name"/>
                  <xsl:variable name="adminName" select="siard:admin"/>
                  <tr>
                    <td class="userValueColumn">
                      <xsl:value-of select="$roleName"/>
                    </td>
                    <td class="userValueColumn">
                      <a href="#user.{$adminName}">
                        <xsl:value-of select="$adminName"/>
                      </a>
                    </td>
                    
                    <td class="userValueColumn">
                      <xsl:value-of select="siard:description"/>
                    </td>
                  </tr>
                </xsl:for-each>
              </table>
              
            </li>
          </ul>
        </xsl:if>
        <xsl:if test="count(siard:privileges/siard:privilege) &gt; 0">
          <ul>
            <li>
              <h2 class="small">
                Privileges
                <a name="privileges"/>
              </h2>
              
              <table class="strong">
                <tr>
                  <th class="horizontalTitleColumn">
                    Type
                  </th>
                  <th class="horizontalTitleColumn">
                    Object
                  </th>
                  <th class="horizontalTitleColumn">
                    Grantor
                  </th>
                  <th class="horizontalTitleColumn">
                    Grantee
                  </th>
                  <th class="horizontalTitleColumn">
                    Option
                  </th>
                  <th class="horizontalTitleColumn">
                    Description
                  </th>
                </tr>
                <xsl:for-each select="siard:privileges/siard:privilege">
                  <xsl:variable name="objectName" select="siard:object"/>
                  <xsl:variable name="privilegeGrantor" select="siard:grantor"/>
                  <xsl:variable name="privilegeGrantee" select="siard:grantee"/>
                  <tr>
                    <td class="userValueColumn">
                      <xsl:value-of select="siard:type"/>
                    </td>
                    <td class="userValueColumn">
                      
                      <xsl:value-of select="$objectName"/>
                      
                    </td>
                    <td class="userValueColumn">
                      <a href="#user.{$privilegeGrantor}">
                        <xsl:value-of select="$privilegeGrantor"/>
                      </a>
                    </td>
                    <td class="userValueColumn">
                      <a href="#user.{$privilegeGrantee}">
                        <xsl:value-of select="$privilegeGrantee"/>
                      </a>
                    </td>
                    <td class="userValueColumn">
                      <xsl:value-of select="siard:option"/>
                    </td>
                    <td class="userValueColumn">
                      <xsl:value-of select="siard:description"/>
                    </td>
                  </tr>
                </xsl:for-each>
              </table>
              
            </li>
          </ul>
        </xsl:if>
      </body>
    </html>
  </xsl:template>
</xsl:stylesheet>';

// -----------------------------------------------------------------------------
$static_torque2siard = '<?xml version="1.0"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:db="http://db.apache.org/torque/4.0/templates/database" xmlns="http://www.bar.admin.ch/xmlns/siard/1.0/metadata.xsd">
	<xsl:output method="xml" indent="yes" encoding="UTF-8" media-type="application/xml"/>
	<xsl:variable name="location">http://www.bar.admin.ch/xmlns/siard/1.0/metadata.xsd metadata.xsd</xsl:variable>
	<!--                        begin params                       -->
	<xsl:param name="description"/>
	<xsl:param name="archiver"/>
	<xsl:param name="archiverContact"/>
	<xsl:param name="dataOwner"/>
	<xsl:param name="dataOriginTimespan"/>
	<xsl:param name="producerApplication"/>
	<xsl:param name="archivalDate"/>
	<xsl:param name="messageDigest"/>
	<xsl:param name="clientMachine"/>
	<xsl:param name="databaseProduct"/>
	<xsl:param name="connection"/>
	<xsl:param name="databaseUser"/>
	<xsl:param name="databaseSchema"/>
	<!--                        end params                         -->
	<xsl:template match="/">
		<xsl:element name="siardArchive">
			<xsl:attribute name="version"><xsl:text>1.0</xsl:text></xsl:attribute>
			<xsl:attribute name="xsi:schemaLocation"><xsl:text>http://www.bar.admin.ch/xmlns/siard/1.0/metadata.xsd metadata.xsd</xsl:text></xsl:attribute>
			<xsl:element name="dbname">
				<xsl:choose>
					<xsl:when test="/db:database/@name=\'\'">
						<xsl:text>unknown</xsl:text>
					</xsl:when>
					<xsl:otherwise>
						<xsl:value-of select="/db:database/@name"/>
					</xsl:otherwise>
				</xsl:choose>
			</xsl:element>
			<xsl:element name="description">
				<xsl:value-of select="$description"/>
			</xsl:element>
			<xsl:element name="archiver">
				<xsl:value-of select="$archiver"/>
			</xsl:element>
			<xsl:element name="archiverContact">
				<xsl:value-of select="$archiverContact"/>
			</xsl:element>
			<xsl:element name="dataOwner">
				<xsl:value-of select="$dataOwner"/>
			</xsl:element>
			<xsl:element name="dataOriginTimespan">
				<xsl:value-of select="$dataOriginTimespan"/>
			</xsl:element>
			<xsl:element name="producerApplication">
				<xsl:value-of select="$producerApplication"/>
			</xsl:element>
			<xsl:element name="archivalDate">
				<xsl:value-of select="$archivalDate"/>
			</xsl:element>
			<xsl:element name="messageDigest">
				<xsl:value-of select="$messageDigest"/>
			</xsl:element>
			<xsl:element name="clientMachine">
				<xsl:value-of select="$clientMachine"/>
			</xsl:element>
			<xsl:element name="databaseProduct">
				<xsl:value-of select="$databaseProduct"/>
			</xsl:element>
			<xsl:element name="connection">
				<xsl:value-of select="$connection"/>
			</xsl:element>
			<xsl:element name="databaseUser">
				<xsl:text>"</xsl:text>
				<xsl:value-of select="$databaseUser"/>
				<xsl:text>"</xsl:text>
			</xsl:element>
			<!-- SCHEMA -->
			<xsl:element name="schemas">
				<xsl:element name="schema">
					<xsl:element name="name">
						<xsl:value-of select="$databaseSchema"/>
					</xsl:element>
					<xsl:element name="folder">
						<xsl:value-of select="$databaseSchema"/>
					</xsl:element>
					<!-- TABLES -->
					<xsl:element name="tables">
						<xsl:apply-templates/>
					</xsl:element>
				</xsl:element>
			</xsl:element>
			<!-- USER -->
			<xsl:element name="users">
				<xsl:element name="user">
					<xsl:element name="name">
						<xsl:value-of select="$databaseUser"/>
					</xsl:element>
				</xsl:element>
			</xsl:element>
		</xsl:element>
	</xsl:template>
	<!-- TABLE -->
	<xsl:template match="/db:database/db:table">
		<xsl:element name="table">
			<xsl:element name="name">
				<xsl:value-of select="@name"/>
			</xsl:element>
			<xsl:element name="folder">
				<xsl:value-of select="db:option[@key=\'folder\']/@value"/>
			</xsl:element>
			<xsl:if test="@description">
				<xsl:element name="description">
					<xsl:value-of select="@description"/>
					<xsl:if test="db:option[@key=\'query\']/@value">
						<!-- Insert a solid line break  -->
						<xsl:text xml:space="preserve">\\u000A</xsl:text>
						<xsl:text>QUERY: </xsl:text>
						<xsl:value-of select="db:option[@key=\'query\']/@value"/>
						<xsl:text>;</xsl:text>
					</xsl:if>
				</xsl:element>
			</xsl:if>
			<!-- COLUMNS -->
			<xsl:element name="columns">
				<xsl:apply-templates/>
			</xsl:element>
			<!-- PRIMARY KEY -->
			<xsl:if test="db:column/@primaryKey=\'true\'">
				<xsl:element name="primaryKey">
					<xsl:element name="name">
						<xsl:text>pk_</xsl:text>
						<xsl:value-of select="@name"/>
					</xsl:element>
					<xsl:for-each select="db:column">
						<xsl:if test="@primaryKey=\'true\'">
							<xsl:element name="column">
								<xsl:value-of select="@name"/>
							</xsl:element>
						</xsl:if>
					</xsl:for-each>
				</xsl:element>
			</xsl:if>
			<!-- FOREIGN KEYS -->
			<xsl:if test="db:foreign-key">
				<xsl:element name="foreignKeys">
					<xsl:for-each select="db:foreign-key">
						<xsl:element name="foreignKey">
							<xsl:element name="name">
								<xsl:value-of select="@name"/>
							</xsl:element>
							<xsl:element name="referencedSchema">
								<xsl:value-of select="$databaseSchema"/>
							</xsl:element>
							<xsl:element name="referencedTable">
								<xsl:value-of select="@foreignTable"/>
							</xsl:element>
							<xsl:element name="reference">
								<xsl:element name="column">
									<xsl:value-of select="db:reference/@local"/>
								</xsl:element>
								<xsl:element name="referenced">
									<xsl:value-of select="db:reference/@foreign"/>
								</xsl:element>
							</xsl:element>
						</xsl:element>
					</xsl:for-each>
				</xsl:element>
			</xsl:if>
			<!-- ROWS -->
			<xsl:element name="rows">
				<xsl:value-of select="db:option[@key=\'rowcount\']/@value"/>
			</xsl:element>
		</xsl:element>
	</xsl:template>
	<!-- COLUMN -->
	<xsl:template match="/db:database/db:table/db:column">
		<xsl:element name="column">
		
			<xsl:element name="name">
				<xsl:value-of select="@name"/>
			</xsl:element>
			
			<xsl:element name="type">
				<xsl:choose>
					<!-- CHAR VARCHAR LONGVARCHAR CLOB -->
					<xsl:when test="@type=\'CHAR\' or @type=\'VARCHAR\' or @type=\'LONGVARCHAR\' or @type=\'CLOB\'">
						<xsl:text>CHARACTER VARYING</xsl:text>
							<xsl:choose>
								<xsl:when test="@size">
									<xsl:text>(</xsl:text>
									<xsl:value-of select="@size"/>
									<xsl:text>)</xsl:text>
								</xsl:when>
								<xsl:otherwise>
									<xsl:text>(255)</xsl:text>
								</xsl:otherwise>
							</xsl:choose>
					</xsl:when>
					<!-- TINYINT SMALLINT INTEGER BIGINT -->
					<xsl:when test="@type=\'TINYINT\' or @type=\'SMALLINT\' or @type=\'INTEGER\' or @type=\'BIGINT\'">
						<xsl:text>INTEGER</xsl:text>
					</xsl:when>
					<!-- NUMERIC DECIMAL -->
					<xsl:when test="@type=\'NUMERIC\' or @type=\'DECIMAL\'">
						<xsl:text>NUMERIC</xsl:text>
						<xsl:if test="@size">
							<xsl:text>(</xsl:text>
							<xsl:value-of select="@size"/>
							<xsl:if test="@scale">
								<xsl:text>,</xsl:text>
								<xsl:value-of select="@scale"/>
							</xsl:if>
							<xsl:text>)</xsl:text>
						</xsl:if>
					</xsl:when>
					<!-- FLOAT REAL DOUBLE -->
					<xsl:when test="@type=\'FLOAT\' or @type=\'REAL\' or @type=\'DOUBLE\'">
						<xsl:text>FLOAT</xsl:text>
						<xsl:if test="@size">
							<xsl:text>(</xsl:text>
							<xsl:value-of select="@size"/>
							<xsl:if test="@scale">
								<xsl:text>,</xsl:text>
								<xsl:value-of select="@scale"/>
							</xsl:if>
							<xsl:text>)</xsl:text>
						</xsl:if>
					</xsl:when>
					<!-- BINARY VARBINARY LONGVARBINARY -->
					<xsl:when test="@type=\'BINARY\' or @type=\'VARBINARY\' or @type=\'LONGVARBINARY\'">
						<xsl:text>BIT VARYING</xsl:text>
							<xsl:choose>
								<xsl:when test="@size">
									<xsl:text>(</xsl:text>
									<xsl:value-of select="@size"/>
									<xsl:text>)</xsl:text>
								</xsl:when>
								<xsl:otherwise>
									<xsl:text>(4096)</xsl:text>
								</xsl:otherwise>
							</xsl:choose>
					</xsl:when>
					<!-- BIT -->
					<xsl:when test="@type=\'BIT\'">
						<xsl:text>BIT</xsl:text>
							<xsl:choose>
								<xsl:when test="@size">
									<xsl:text>(</xsl:text>
									<xsl:value-of select="@size"/>
									<xsl:text>)</xsl:text>
								</xsl:when>
								<xsl:otherwise>
									<xsl:text>(8)</xsl:text>
								</xsl:otherwise>
							</xsl:choose>
					</xsl:when>
					<!-- BOOLEANINT BOOLEANCHAR -->
					<xsl:when test="@type=\'BOOLEANINT\' or @type=\'BOOLEANCHAR\'">
						<xsl:text>BOOLEAN</xsl:text>
					</xsl:when>
					<!-- BLOB -->
					<xsl:when test="@type=\'BLOB\'">
						<xsl:text>BINARY LARGE OBJECT</xsl:text>
					</xsl:when>
					<!-- REF -->
					<xsl:when test="@type=\'REF\'">
						<xsl:text>CHARACTER VARYING (255)</xsl:text>
					</xsl:when>
					<!-- all other data type -->
					<xsl:otherwise>
						<xsl:value-of select="@type"/>
						<xsl:if test="@size">
							<xsl:text>(</xsl:text>
							<xsl:value-of select="@size"/>
							<xsl:if test="@scale">
								<xsl:text>,</xsl:text>
								<xsl:value-of select="@scale"/>
							</xsl:if>
							<xsl:text>)</xsl:text>
						</xsl:if>
					</xsl:otherwise>
				</xsl:choose>
			</xsl:element>
			
			<xsl:element name="typeOriginal">
				<xsl:value-of select="@type"/>
				<xsl:if test="@size">
					<xsl:text>(</xsl:text>
					<xsl:value-of select="@size"/>
					<xsl:if test="@scale">
						<xsl:text>,</xsl:text>
						<xsl:value-of select="@scale"/>
					</xsl:if>
					<xsl:text>)</xsl:text>
				</xsl:if>
			</xsl:element>
			
			<xsl:element name="nullable">
				<xsl:choose>
					<xsl:when test="@required">
						<xsl:choose>
							<xsl:when test="@required=\'true\'">
								<xsl:text>false</xsl:text>
							</xsl:when>
							<xsl:otherwise>
								<xsl:text>true</xsl:text>
							</xsl:otherwise>
						</xsl:choose>
					</xsl:when>
					<xsl:otherwise>
						<xsl:text>true</xsl:text>
					</xsl:otherwise>
				</xsl:choose>
			</xsl:element>
			
			<xsl:if test="@description">
				<xsl:element name="description">
					<xsl:value-of select="@description"/>
				</xsl:element>
			</xsl:if>
		</xsl:element>
	</xsl:template>
</xsl:stylesheet>
';

// -----------------------------------------------------------------------------
$static_torque2csvschema = '<?xml version="1.0" encoding="UTF-8"?>
<!-- edited with XMLSpy v2012 rel. 2 (http://www.altova.com) by Thomas Bula (Bundesamt für Informatik und Telekommunikation) -->
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:db="http://db.apache.org/torque/4.0/templates/database">
	<xsl:output method="xml" indent="no" encoding="ISO-8859-1" omit-xml-declaration="yes"/>
	<xsl:variable name="location">http://www.bar.admin.ch/xmlns/siard/1.0/metadata.xsd metadata.xsd</xsl:variable>
	<!--                        begin params                       -->
	<xsl:param name="file_mask"/>
	<xsl:param name="column_names"/>
	<xsl:param name="delimited"/>
	<xsl:param name="charset"/>
	<!--                        end params                          -->
	<xsl:variable name="newline">
		<xsl:text>&#xD;&#xA;</xsl:text>
	</xsl:variable>
	<!--                        end variables                       -->
	<xsl:template match="/">
		<xsl:apply-templates/>
	</xsl:template>
	<!--                          TABLES                            -->
	<xsl:template match="/db:database/db:table">
		<xsl:value-of select="$newline"/>
		<xsl:text>[</xsl:text>
		<xsl:value-of select="@name"/>
		<xsl:value-of select="$file_mask"/>
		<xsl:text>]</xsl:text>
		<xsl:value-of select="$newline"/>
		<xsl:text>ColNameHeader=</xsl:text>
		<xsl:value-of select="$column_names"/>
		<xsl:value-of select="$newline"/>
		<xsl:text>Format=</xsl:text>
		<xsl:value-of select="$delimited"/>
		<xsl:value-of select="$newline"/>
		<xsl:text>MaxScanRows=25</xsl:text>
		<xsl:value-of select="$newline"/>
		<xsl:text>CharacterSet=</xsl:text>
		<xsl:value-of select="$charset"/>
		<xsl:value-of select="$newline"/>
		<!--                           ROWS                            -->
		<xsl:for-each select="db:column">
			<xsl:text>Col</xsl:text>
			<xsl:value-of select="position()"/>
			<xsl:text>=</xsl:text>
			<xsl:value-of select="@name"/>
			<xsl:text> </xsl:text>
			<xsl:text/>
			<xsl:choose>
				<xsl:when test="@type=\'INTEGER\'">
					<xsl:text>Integer</xsl:text>
				</xsl:when>
				<xsl:when test="@type=\'DECIMAL\'">
					<xsl:text>Char Width 255</xsl:text>
				</xsl:when>
				<xsl:when test="@type=\'FLOAT\'">
					<xsl:text>Float</xsl:text>
				</xsl:when>
				<xsl:when test="@type=\'DATE\'">
					<xsl:text>Char Width 255</xsl:text>
				</xsl:when>
				<xsl:when test="@type=\'VARCHAR\'">
					<xsl:text>Char </xsl:text>
					<xsl:choose>
						<xsl:when test="@size">
							<xsl:text>Width </xsl:text>
							<xsl:value-of select="@size"/>
						</xsl:when>
						<xsl:otherwise>
							<xsl:text>Width 255</xsl:text>
						</xsl:otherwise>
					</xsl:choose>
				</xsl:when>
				<xsl:otherwise>
					<xsl:text>Char Width 255</xsl:text>
				</xsl:otherwise>
			</xsl:choose>
			<xsl:value-of select="$newline"/>
		</xsl:for-each>
	</xsl:template>
</xsl:stylesheet>
';
?>
