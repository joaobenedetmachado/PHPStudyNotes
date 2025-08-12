document.getElementById('search-form').addEventListener('submit', function(event){
    event.preventDefault() // Previne que a página recarregue após o submit
    
    const form = event.target;
    const pokemonName = form.elements['pokemon_name'].value;

    console.log(pokemonName);

    // Colocando os elementos do HTML em variáveis para que possamos alterar (DOM)
    const pokemonImage = document.getElementById('pokemon-image');
    const pokemonNameElement = document.getElementById('pokemon-name');
    const pokemonIdElement = document.getElementById('pokemon-id');
    const pokemonTypeElement = document.getElementById('pokemon-type');
    const pokemonDescriptionElement = document.getElementById('pokemon-description');

    // Setta as variáveis por padrão assim
    pokemonNameElement.textContent = "Buscando...";
    pokemonIdElement.textContent = "";
    pokemonTypeElement.textContent = "";
    pokemonDescriptionElement.textContent = "";
    pokemonImage.src = "";

    // Conexão com o PHP
    fetch(`backend.php?pokemon_name=${pokemonName}`) // O que está dentro das chaves é o pokemonName = form.elements['pokemon_name'].value;
        .then(response => response.json())
        .then(data => {
            console.log(data);
            // Se a requisição der certo vai cair aqui
            if(data.success){
                pokemonImage.src = data.image;
                pokemonNameElement.textContent = data.name;
                pokemonIdElement.textContent = `N°: ${data.id}`;
                pokemonTypeElement.textContent = `Tipo: ${data.type}`;
                pokemonDescriptionElement.textContent = data.description;
            }
            else{
                pokemonImage.src = data.image;
                pokemonNameElement.textContent = data.name;
                pokemonIdElement.textContent = data.id;
                pokemonTypeElement.textContent = data.type;
                pokemonDescriptionElement = data.description;
            }
        })
        .catch(error => {
            // Se der erro na requisição vai cair aqui
            console.error('Erro: ', error);
        });

});