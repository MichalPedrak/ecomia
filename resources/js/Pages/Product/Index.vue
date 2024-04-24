<script setup>

import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import {Head} from "@inertiajs/vue3";
import { TailwindPagination } from 'laravel-vue-pagination';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';

defineProps({
    products: {
        type: Object,
    },
    data: {
        type: Object,
    },

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

                    <DataTable paginator :rows="20" :rowsPerPageOptions="[5, 10, 20, 50, 100, 500]" :value="products" tableStyle="min-width: 50rem">
                        <Column field="category" header="Kategoria">
                            <template #body="slotProps">
                                <span class="product-category">{{ slotProps.data.category }}</span>

                            </template>
                        </Column>
                        <Column field="image" header="Zdjęcie">
                            <template #body="slotProps">
                                <img  :src="`${slotProps.data.image}`" :alt="slotProps.data.image" class="w-6rem border-round product-image" />
                            </template>
                        </Column>
                        <Column field="name" header="Nazwa">
                            <template #body="slotProps">
                                <a target="_blank" :href="`/produkty/${slotProps.data.ean}` " class="product-name">{{ slotProps.data.name }}</a>

                            </template></Column>
                        <Column field="ean" header="Ean"></Column>
<!--                        <Column field="data_collector.price" header="Dane"></Column>-->
                        <Column field="quantity" header="Dane">
                        </Column>


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
    //margin: 20px auto;
    //width: 100%;
    object-fit: contain
;
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
</style>
