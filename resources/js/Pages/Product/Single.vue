<script setup>

import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import {Head} from "@inertiajs/vue3";
import Chart from 'primevue/chart';
import { ref, onMounted, watch, reactive } from "vue";
import Dropdown from 'primevue/dropdown';
import axios from 'axios'

const props = defineProps({
    product: {
        type: Object,
    },
    data: {
        type: Object,
    },

});

onMounted(() => {
    chartData.value = setChartData();
    chartOptions.value = setChartOptions();
});



const chartData = ref();
const chartOptions = ref();


let dataProp =  props.data;
const prices = ref([]);
const quantities = ref([]);
const dates = ref([]);
const monthNames = [
  "styczeń", "luty", "marzec", "kwiecień", "maj", "czerwiec",
  "lipiec", "sierpień", "wrzesień", "październik", "listopad", "grudzień"
];

dataProp.forEach(item => {
    prices.value.push(parseFloat(item.price)); // Assuming price is a string, convert it to a float if needed
    quantities.value.push(parseInt(item.quantity)); // Assuming quantity is a string, convert it to an integer if needed

    const date = new Date(item.created_at);
    const day = date.getDate();
    const month = monthNames[date.getMonth()]; // Adding 1 because months are zero-indexed
    // const year = date.getFullYear();

    const hours = date.getHours();
    const minutes = date.getMinutes();
    // const seconds = date.getSeconds();

    const formattedDate = `${day} ${month} - ${hours} ${minutes} `;
    dates.value.push(formattedDate); // Assuming quantity is a string, convert it to an integer if needed

});

const setChartData = () => {
    const documentStyle = getComputedStyle(document.documentElement);

    return {
        labels: dates,
        datasets: [
            {
                label: 'Ilośc',
                data: quantities,
                fill: false,
                borderColor: documentStyle.getPropertyValue('--cyan-500'),
                tension: 0.4
            },
            {
                label: 'Cena',
                data: prices,
                fill: false,
                borderColor: documentStyle.getPropertyValue('--gray-500'),
                tension: 0.4
            }
        ]
    };
};
const setChartOptions = () => {
    const documentStyle = getComputedStyle(document.documentElement);
    const textColor = documentStyle.getPropertyValue('--text-color');
    const textColorSecondary = documentStyle.getPropertyValue('--text-color-secondary');
    const surfaceBorder = documentStyle.getPropertyValue('--surface-border');

    return {
        maintainAspectRatio: false,
        aspectRatio: 0.6,
        plugins: {
            legend: {
                labels: {
                    color: textColor
                }
            }
        },
        scales: {
            x: {
                ticks: {
                    color: textColorSecondary
                },
                grid: {
                    color: surfaceBorder
                }
            },
            y: {
                ticks: {
                    color: textColorSecondary
                },
                grid: {
                    color: surfaceBorder
                }
            }
        }
    };
}   


const selectedCity = ref();
const cities = ref([
    { name: 'Dzisiaj', periode: '1' },
    { name: 'Tydzień', periode: '7' },
    { name: 'Dwa Tygodnie', periode: '14' },
    { name: 'Miesiąc', periode: '31' },
    { name: 'Cały okres', periode: '-1' },

]);

watch(selectedCity, async ( newPeriod, oldPeriod) => { 
    console.log(newPeriod);
    let newDates = ref([]);
    let newPrices = ref([]);
    let newQuantities = ref([]);

    try {
               
        axios.get('/produkty/' + props.product.ean +'/period/' + newPeriod.periode, {
                headers: {
                    'Content-type': 'application/json',
                }
            }).then(function (response) {
                    console.log(response)

                    response.data.data.forEach(item => {
                        newPrices.value.push(parseFloat(item.price)); 
                        newQuantities.value.push(parseInt(item.quantity));
                        const date = new Date(item.created_at);
                        const day = date.getDate();
                        const month = monthNames[date.getMonth()]; 
            

                        const formattedDate = `${day} ${month} `;
                        newDates.value.push(formattedDate); 

                });
                console.log("_____________")
                console.log(newDates,newPrices,  newQuantities)

                dates.value = newDates.value;
                prices.value = newPrices.value;
                quantities.value = newQuantities.value;


                })
            .catch(function (errorResponse) {
                console.log(errorResponse)
            });


            





    // dates.value.push("TEST TEST");
    //     prices.value = [20, 40, 40, 70, 60];
    //     quantities.value = [20, 60, 50, 10, 60];
    } catch (error) {
      console.log(error)
    }

   
  }
)

</script>


<template>
    <Head :title="product.name" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Produkty</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">



                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">

                    <div class="singleProduct flex">
                        <div class="w-1/2">
                            <img height="300" width="300" :src="product.image">
                        </div>
                        <div class="w-1/2">
                            <h2 class="text-3xl mb-5">{{ product.name }}</h2>
                            <p>{{ product.ean }}</p>
                            <p>{{ product.category }}</p>
                        </div> 
                    </div>
                  
                    

                    <div class="card flex justify-content-center w-48">
                        <Dropdown v-model="selectedCity" :options="cities" optionLabel="name" placeholder="Wybierz okres" class="w-full md:w-14rem" />
                    </div>




                    <Chart type="line" :data="chartData" :options="chartOptions" class="h-64" />
                

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

</style>
