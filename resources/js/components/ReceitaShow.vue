<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col">
                <div class="card">
                    <div class="card-header"><h4>{{ receita.titulo }}</h4></div>

                    <div class="card-body">
                        <img :src="'/storage/'+receita.imagem" :alt="receita.titulo" class="w-100">
                        <div class="row m-0">
                            <span class="col bg-light border">Tempo: {{ receita.tempo_preparo }}</span>
                            <span class="col bg-light border">Porções: {{ receita.qtd_porcao }} </span>
                            <span class="col bg-light border">Publicado: {{ receita.created_at }}</span>
                            <span class="col bg-light border">Atualizado: {{ receita.updated_at }}</span>
                            <span class="col bg-light border">Tipo: {{ receita.tipo }}</span>
                        </div>

                        <div class="mt-4">
                            <h4>Ingredientes</h4>
                            <ul>
                                <li v-for="i, chaveI in ingredientes" :key="chaveI">
                                    {{ i }}
                                </li>
                            </ul>
                        </div>

                        <div class="mt-4">
                            <h4>Modo de preparo</h4>
                            <p>
                                {{ receita.modo_preparo }}
                            </p>
                        </div>

                        <div class="mt-4">
                            <h4>Observações / Dicas</h4>
                            <p>
                                {{ receita.observacao }}
                            </p>
                        </div>
                        
                        <div class="mt-4">
                            <h5>Categorias</h5>
                            <ul>
                                <li v-for="c, chaveC in categorias" :key="chaveC">
                                    {{ c }}
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: [
            'id',
        ],
        data() {
            return {
                receita: [],
                ingredientes: [],
                categorias: []
            }
        },
        methods: {
            carregarReceita() {
                let url = 'http://localhost:8000/api/receita/'+this.id

                axios.get(url)
                    .then(response => {
                        this.receita = response.data

                        let ingredienesJson = response.data.ingredientes
                        let ingredientesArray = JSON.parse(ingredienesJson)
                        this.ingredientes = ingredientesArray

                        let categoriaJson = response.data.categorias
                        let categoriasArray = JSON.parse(categoriaJson)
                        this.categorias = categoriasArray

                    })
                    .catch(errors => {
                        console.log(errors);
                    })
            }
        },
        mounted() {
            this.carregarReceita()
        }
    }
</script>
