var passw = document.getElementById('passw');
var inCarac = document.getElementById('numeroCaracteres');
var simb = document.getElementById('simbolos');
var num = document.getElementById('numeros');
var mayu = document.getElementById('mayusculas');

//Creamos un objeto con la configuración
var config = {
    carac: parseInt(inCarac.value),
    simbolos: true,
    numeros: true,
    mayusculas: true,
    minusculas: true
}

//Objeto con lo que contendrá cada categoria
var caracteres = {
    numeros: '0 1 2 3 4 5 6 7 8 9',
    simbolos: '! @ # $ % & ¿ ? ( ) / - . ,',
    mayusculas: 'A B C D E F G H I J K L M N O P Q R S T U V W X Y Z',
    minusculas: 'a b c d e f g h i j k l m n o p q r s t u v w x y z'
}


//Evitar enviar y refrescar
passw.addEventListener('submit', (noSubmit) => noSubmit.preventDefault());



//Si selecciona o no
var simbCheck = simb.checked = true;
var numCheck = num.checked = true;
var mayuCheck = mayu.checked = true;
simb.addEventListener('change', (simbCheck) => config.simbolos = !config.simbolos);
num.addEventListener('change', (numCheck) => config.numeros = !config.numeros);
mayu.addEventListener('change', (mayuCheck) => config.mayusculas = !config.mayusculas);

//Boton generar
passw.elements.namedItem('generar').addEventListener('click', () => generarPass());

//Generar password
function generarPass() {
    config.carac = inCarac.value;
    var final = '';
    var password = '';

    //accedemos a cada una de las propiedades
    for(propiedad in config) { // comprobamos si las propiedades son true
        if (config[propiedad] == true) { 
            final += caracteres[propiedad] + ' '; //Si son true se agregara el valor de la propiedad
        }
    }
    final = final.trim(); //Quita espaciados al principio y al final del texto
    final = final.split(' '); //Separa una cadena en arreglo cada espaciado que encuentre

    //Genera contraseña letra por letra al azar
    for(var i = 0; i < config.carac; i++) {
        password += final[Math.floor(Math.random() * final.length)];
    }
    passw.elements.namedItem('pass').value = password;
}
generarPass();