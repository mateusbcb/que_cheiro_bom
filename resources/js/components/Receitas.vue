<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12">
                <card-receita-component :dados="receitas">
                        
                </card-receita-component>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                urlBase: 'http://localhost:8000/api/receita',
                receitas: {
                    data: []
                }
            }
        },
        methods: {
            carregarLista() {
                let url = this.urlBase

                axios.get(url)
                    .then(response => {
                        this.receitas = response.data.data
                    })
                    .catch(errors => {
                        console.log(errors);
                    })
            }
        },
        mounted() {
            this.carregarLista()
        }
    }
</script>
