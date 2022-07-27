<?php
$datosExtras='<Signature xmlns="http://www.w3.org/2000/09/xmldsig#">
    <SignedInfo>
      <CanonicalizationMethod Algorithm="http://www.w3.org/TR/2001/REC-xml-c14n-20010315" />
      <SignatureMethod Algorithm="http://www.w3.org/2001/04/xmldsig-more#rsa-sha256" />
      <Reference URI="">
        <Transforms>
          <Transform Algorithm="http://www.w3.org/2000/09/xmldsig#enveloped-signature" />
          <Transform Algorithm="http://www.w3.org/TR/2001/REC-xml-c14n-20010315#WithComments" />
        </Transforms>
        <DigestMethod Algorithm="http://www.w3.org/2001/04/xmlenc#sha256" />
        <DigestValue>test</DigestValue>
      </Reference>
    </SignedInfo><SignatureValue /><KeyInfo /><Object /></Signature>';


require '../vendor/autoload.php';
use RobRichards\XMLSecLibs\XMLSecurityDSig;
use RobRichards\XMLSecLibs\XMLSecurityKey;
$doc = new DOMDocument();
$doc->loadXML($datosExtras);

// Create a new Security object 
$objDSig = new XMLSecurityDSig();
// Use the c14n exclusive canonicalization
$objDSig->setCanonicalMethod(XMLSecurityDSig::EXC_C14N);
// Sign using SHA-256
$objDSig->addReference(
    $doc, 
    XMLSecurityDSig::SHA256, 
    array('http://www.w3.org/2000/09/xmldsig#enveloped-signature')
);

// Create a new (private) Security key
$objKey = new XMLSecurityKey(XMLSecurityKey::RSA_SHA256, array('type'=>'private'));
/*
If key has a passphrase, set it using
$objKey->passphrase = '<passphrase>';
*/
// Load the private key
$objKey->loadKey('path/to/file/privatekey.pem', TRUE);

// Sign the XML file
$objDSig->sign($objKey);

// Add the associated public key to the signature
$objDSig->add509Cert(file_get_contents('./path/to/file/CORPORACIONFARMACIASBOLIVIASA.csr'));

// Append the signature to the XML
$objDSig->appendSignature($doc->documentElement);
// Save the signed XML
// $doc->save('path/to/signed.xml');
echo $doc->saveXML();


?>

