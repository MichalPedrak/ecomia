<script setup>

import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import {Head} from "@inertiajs/vue3";
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';

import { FilterMatchMode } from 'primevue/api';
import { ref } from "vue";
import InputText from 'primevue/inputtext';

defineProps({
    products: {
        type: Object,
    },
    data: {
        type: Object,
    },

});
function productUser(id, state){

     if(!id) return ;

     let method = state != null ? 'destroy' : 'add';

     

    axios.post('/produkty/obserwowane/' + method + '/' + id, {
            headers: {
                'Content-type': 'application/json',
            }
        }).then(function (response) {
                console.log(response)

            })
        .catch(function (errorResponse) {
            console.log(errorResponse)
        });


}

function addWeek(txtDate){
    var today = new Date();
    var nextWeek = new Date(today.getFullYear(), today.getMonth(), today.getDate() - 7);

    //If nextWeek is greater (later) than the value of the input date, alert...
    if (nextWeek < Date.parse(txtDate)) {
        return 'Nowosc';
    }


    }
    const loading = ref(false);
    const filters = ref({
    global: { value: null, matchMode: FilterMatchMode.CONTAINS },
    name: { value: null, matchMode: FilterMatchMode.STARTS_WITH },
 
});

</script>


<template>
    <Head title="Produkty" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Produkty</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">


                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">

                    <DataTable v-model:filters="filters"  paginator filterDisplay="row" :loading="loading"  
        :globalFilterFields="['name']" :rows="20" :rowsPerPageOptions="[5, 10, 20, 50, 100, 500]" :value="products" tableStyle="min-width: 50rem">

        <template #header>
                <div class="flex justify-content-end">
                    <IconField iconPosition="left">
                        <InputIcon>
                            <i class="pi pi-search" />
                        </InputIcon>
                        <InputText v-model="filters['global'].value" placeholder="Szukaj.." />
                    </IconField>
                </div>
            </template>
            <template #empty> Nie znaleziono produktów. </template>
            <template #loading> Ładowanie </template>
      
                        
                        <Column field="watched" header="Obserwowane">
                            <template #body="slotProps">
                                <span @click="productUser(slotProps.data.id,  slotProps.data.user_id)" class="product-watched"  v-html='slotProps.data.user_id != null ? `<input type="checkbox" checked >` : `<input type="checkbox" >` '>
                                    
                                </span>
                               
                            </template>
                        </Column>
                        <Column field="category" header="Kategoria">
                            <template #body="slotProps">
                                <span class="product-category">{{ slotProps.data.category }}</span>

                            </template>
                        </Column>
                        <Column field="image" header="Zdjęcie">
                            <template #body="slotProps">
                                {{  addWeek(slotProps.data.created_at) }}
                                <img  :src="`${slotProps.data.image}`" :alt="slotProps.data.image" class="w-6rem border-round product-image" />
                            </template>
                        </Column>
                        <Column field="name" header="Nazwa">
                            <template #body="slotProps">
                                <a target="_blank" :href="`/produkty/${slotProps.data.ean}` " class="product-name">{{ slotProps.data.name }}</a>
                             
                            </template>
                            <!-- <template #filter="{ filterModel, filterCallback }">
                                <input v-model="filterModel.value" type="text" @input="filterCallback()" class="p-column-filter" placeholder="Search by name" />
                            </template> -->
                        </Column>
                        <Column field="ean" header="Ean"></Column>
                 


                    </DataTable>

<!--                    <div class="products-wrapper">-->
<!--                        <div class="products">-->
<!--                            <div class="product shadow sm:rounded-lg" v-for="product in products" :key="product.id">-->
<!--                                <span class="product-category">{{ product.category }}</span>-->
<!--                                <img class="product-image" :src="product.image" />-->
<!--                                <h2 class="product-title">{{ product.name }}</h2>-->
<!--                                <span class="product-ean">{{ product.ean }}</span>-->


<!--                            </div>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                    <TailwindPagination :data="products" @pagination-change-page="getProducts"></TailwindPagination>-->
                </div>

            </div>
        </div>
    </AuthenticatedLayout>
</template>


<style scoped>
.products{
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
}
.product{
    display: flex;
    flex-wrap: wrap;
    flex: 1 1 0;
    flex-basis: 20%;
    padding: 25px;
}
.product-title{
    font-size: 14px;
}
.product-image{
    height: 100px;
    object-fit: contain;
}
.product-ean{
    color: #6b7280;
    font-size: 12px;
    width: 100%;
}
.product-category{
    font-size: 12px;

    background: #e2e8f0;
    border-radius: 15px;
    margin: auto;
    padding: 5px 12px;
}

.p-inputtext {
 
  
    font-size: 1rem;
    color: #334155;
    background: #ffffff;
    padding: 0.5rem 0.75rem;
    border: 1px solid #cbd5e1;
    transition: background-color 0.2s, color 0.2s, border-color 0.2s, box-shadow 0.2s, outline-color 0.2s;
    appearance: none;
    border-radius: 6px;
    outline-color: transparent;
  }
  .p-inputtext:enabled:hover {
    border-color: #94a3b8;
  }
  .p-inputtext:enabled:focus {
    outline: 1px solid var(--p-focus-ring-color);
    outline-offset: -1px;
    box-shadow: none;
    border-color: #94a3b8;
  }
  .p-inputtext.p-invalid.p-component {
    border-color: #f87171;
  }
  .p-inputtext.p-variant-filled {
    background-color: #f8fafc;
  }
  .p-inputtext.p-variant-filled:enabled:hover {
    background-color: #f8fafc;
  }
  .p-inputtext.p-variant-filled:enabled:focus {
    background-color: #ffffff;
  }
  .p-inputtext.p-inputtext-sm {
    font-size: 0.875rem;
    padding: 0.4375rem 0.65625rem;
  }
  .p-inputtext.p-inputtext-lg {
    font-size: 1.25rem;
    padding: 0.625rem 0.9375rem;
  }

</style>
