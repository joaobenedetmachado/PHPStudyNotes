document.getElementById('search-form').addEventListener('submit', function(event){
    CatImage = ''

    // Conexão com o PHP
    fetch(`backend.php`) // O que está dentro das chaves é o pokemonName = form.elements['pokemon_name'].value;
        .then(response => response.json())
        .then(data => {
            console.log(data);
            // Se a requisição der certo vai cair aqui
            if(data.success){
                CatImage = data.image;
                console.log(CatImage)
            }
            else{
                console.log('erro')
            }
        })
        .catch(error => {
            // Se der erro na requisição vai cair aqui
            console.error('Erro: ', error);
        });

});