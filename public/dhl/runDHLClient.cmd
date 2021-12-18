set XML_PATH=.\lib
set CLASSPATH=.;%JAVA_HOME%\lib;%XML_PATH%\soap.jar;%XML_PATH%\xalan.jar;%XML_PATH%\xerces1.4.jar;%XML_PATH%\log4j-1.2.8.jar;%XML_PATH%\commons-lang-2.4.jar

set RESPONSE_PATH=TransformXMLtoPDF\ResponseXMLS\
set SERVER_URL=https://xmlpitest-ea.dhl.com/XMLShippingServlet
set INPUT_FILE=TransformXMLtoPDF\RequestXML\ShipmentValidation_v10.0_US-DE_request.xml
set FUTURE_DAY=false
java DHLClient %INPUT_FILE% %SERVER_URL% %RESPONSE_PATH% %FUTURE_DAY%