<script setup>
import { ref } from 'vue';
import { MinusCircleOutlined, PlusOutlined } from '@ant-design/icons-vue'
import { meals } from "../utils/constants.js";
import {v4 as uuidv4 } from 'uuid';

const currentStep = ref(0)
const formRef = ref()
const listRestaurant = ref([])
const listDish = ref([])

const dataOrder = ref({
    meal: '',
    numberPeople: 1,
    restaurant: '',
    dishes : [
        {id: uuidv4(), name: '', servings: 1}
    ]
})

const steps = ['step 1', 'step 2', 'step 3', 'review']

const nextStepSubmit = async () => {
    formRef.value.validate()
        .then(() => {
            if(currentStep.value < steps.length - 1) {
                currentStep.value++

                if(currentStep.value === 1) {
                    axios({
                        method: 'get',
                        url: `/api/v1/restaurants?meal=${dataOrder.value.meal}`,
                    })
                        .then(res => {
                            if (res.status === 200) {
                                listRestaurant.value = res.data
                            }
                        })
                }

                if(currentStep.value === 2) {
                    axios({
                        method: 'get',
                        url:`/api/v1/dishes?meal=${dataOrder.value.meal}&restaurant=${dataOrder.value.restaurant}`
                    })
                        .then(res => {
                            if (res.status === 200) {
                                listDish.value = res.data
                            }
                        })
                }
            }
        })
        .catch (error => {
            console.log(error)
        })
}

const submitOrder = async () => {
    try {
        let numberPeople = dataOrder.value.numberPeople;
        const toTalDish = dataOrder.value.dishes.reduce((accumulator, currentValue) =>
            accumulator + currentValue.servings
        , 0)

        if(toTalDish >= numberPeople) {
            const response = await axios.post('/api/v1/orders', {
                meal: dataOrder.value.meal,
                restaurant: dataOrder.value.restaurant,
                number_of_people: dataOrder.value.numberPeople,
                dishes: dataOrder.value.dishes
            })

            if(response.data.status)
                currentStep.value = 0
                formRef.value.resetFields()
        } else {
            alert('Total servings >= no of people')
        }

    } catch (e) {
        console.log(e)
    }
}

const prevStep = () => {
    if(currentStep.value > 0) {
        currentStep.value--
    }
}

const addDish = () => {
    dataOrder.value.dishes.push({
        id: uuidv4(), name: '', servings: 1
    })
}

const onChangeDish = (item) => {
    listDish.value.forEach(dish => {
        if(dish.name === item.name)
            item.idDish = dish.id
            return true
    })

    const listFilter = {}
    dataOrder.value.dishes.forEach(dish => {
        if(listFilter[dish.idDish]) {
            listFilter[dish.idDish].servings += dish.servings
        } else {
            listFilter[dish.idDish] = {...dish}
        }
    })
    dataOrder.value.dishes = Object.values(listFilter)
}

const checkNumberPeople = async (_rule, value) => {
    if(!value) {
        return Promise.reject('Please input number of people')
    }

    if(value > 10) {
        return Promise.reject('Number of people < 10')
    }

    return Promise.resolve()
}

const removeDish = (item) => {
    dataOrder.value.dishes = dataOrder.value.dishes.filter( d => d.id !== item.id)
}

const rules = {
    meal: [
        { required: true, message: 'Please input your meal!' }
    ],
    numberPeople: [
        { validator: checkNumberPeople,  trigger: 'change' }
    ],
    restaurant: [
        { required: true, message: 'Please input your username!' }
    ]
}
</script>

<template>
    <a-card title="Form Orders">
        <a-steps :current="currentStep">
            <a-step title="Step 1"/>
            <a-step title="Step 2"/>
            <a-step title="Step 3"/>
            <a-step title="Review"/>
        </a-steps>

        <a-form
            layout="vertical"
            ref="formRef"
            :model="dataOrder"
            :rules="rules"
        >
            <div v-if="currentStep === 0" class="form-step-1 form">
                <a-row :gutter="30" >
                    <a-col :span="12">
                        <a-form-item name="meal" label="Please select a meal" >
                            <a-select v-model:value="dataOrder.meal" class="w-100" >
                                <a-select-option v-for="meal in meals" :value="meal.key" :key="meal.key">
                                    {{ meal.label }}
                                </a-select-option>
                            </a-select>
                        </a-form-item>
                    </a-col>
                    <a-col :span="12">
                        <a-form-item name="numberPeople" label="Please enter number of people">
                            <a-input-number :min="1" :max="10" v-model:value="dataOrder.numberPeople" class="w-100"/>
                        </a-form-item>
                    </a-col>
                </a-row>
            </div>
            <div v-if="currentStep === 1" class="form-step-2 form">
                <a-row :gutter="30">
                    <a-col :span="12" :offset="6">
                        <a-form-item name="restaurant" label="Please Select a Restaurant">
                            <a-select v-model:value="dataOrder.restaurant" class="w-100">
                                <a-select-option v-for="restaurant in listRestaurant"
                                :key="restaurant.id" :value="restaurant.name"
                                >{{ restaurant.name }}</a-select-option>
                            </a-select>
                        </a-form-item>
                    </a-col>
                </a-row>
            </div>
            <div v-if="currentStep === 2" class="form-step-3 form">
                <a-row
                    v-for="(item, index) in dataOrder.dishes"
                    :key="item.id"
                    :gutter="30" align="middle">
                    <a-col :span="12">
                        <a-form-item label="Please Select a Dish">
                            <a-select class="w-100" v-model:value="item.name" @change="onChangeDish(item)">
                                <a-select-option v-for="dish in listDish" :value="dish.name" :key="dish.id" >
                                    {{ dish.name }}
                                </a-select-option>
                            </a-select>
                        </a-form-item>
                    </a-col>
                    <a-col :span="10">
                        <a-form-item label="Please enter no. of servings">
                            <a-input-number :min="1" class="w-100" v-model:value="item.servings"/>
                        </a-form-item>
                    </a-col>
                    <a-col :span="2" v-if="index !== 0">
                        <MinusCircleOutlined class="dynamic-delete-button" style="cursor: pointer; font-size: 18px" @click="removeDish(item)"/>
                    </a-col>
                </a-row>

                <a-row>
                    <a-col :span="12">
                        <a-button type="dashed" @click="addDish">
                            <PlusOutlined />
                            Add Dish
                        </a-button>
                    </a-col>
                </a-row>
            </div>
            <div v-if="currentStep === 3" class="form-step-4 form" >

                <a-descriptions>
                    <a-descriptions-item label="Meal">{{ dataOrder.meal }}</a-descriptions-item>
                    <a-descriptions-item label="No .People">{{ dataOrder.numberPeople }}</a-descriptions-item>
                    <a-descriptions-item label="restaurant">{{ dataOrder.restaurant }}</a-descriptions-item>
                    <a-descriptions-item label="Dishes">
                        <br>
                        <div v-for="dish in dataOrder.dishes" :key="dish.id" style="border-right: 1px solid #eee">
                            <span style="padding: 0 10px">{{ dish.name }}:</span>
                            <span style="padding: 0 10px">{{ dish.servings }}</span>
                        </div>
                        <br>
                    </a-descriptions-item>
                </a-descriptions>
            </div>
            <div class="gr-button">
                <a-row>
                    <a-col :span="12">
                        <a-button v-if="currentStep > 0" @click="prevStep">Previous</a-button>
                    </a-col>
                    <a-col :span="12" class="text-right">
                        <a-button v-if="currentStep < steps.length - 1" @click="nextStepSubmit">Next</a-button>
                        <a-button v-if="currentStep === 3" type="primary" @click="submitOrder">Submit</a-button>
                    </a-col>
                </a-row>
            </div>
        </a-form>
    </a-card>
</template>

<style scoped>

</style>
