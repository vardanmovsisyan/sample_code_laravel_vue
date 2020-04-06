<!-- 
    A Vue component styled with Vuetify 1
-->

<template>
    <main>
        <aside class="text-center aside-top hidden-sm-and-down">
            <v-img src="images/banner_728x90.gif" alt="ad" width="728px" height="90px"></v-img>
        </aside>
        <aside class="hidden-md-and-up text-center aside-top">
            <v-img src="images/banner_320x50.png" alt="ad" width="320px" height="50px"></v-img>
        </aside>
        <section>
            <v-layout
                    row
                    wrap
                    justify-center
                    class="mt-5"
            >
                <v-flex xs12 sm12 md12 lg12>
                    <h2 class="dark-text" :class="{'display-1': $vuetify.breakpoint. smAndDown, 'display-3': $vuetify.breakpoint. mdAndUp}">Merge PDF</h2>
                </v-flex>
                <v-flex xs12 sm12 md7 lg9>
                    <v-stepper v-model="e1" class="mr-3">
                        <v-stepper-header>
                            <v-stepper-step :complete="e1 > 1" step="1">Upload pdf</v-stepper-step>

                            <v-divider class="stepper-divider"></v-divider>

                            <v-stepper-step step="2">Download the result</v-stepper-step>
                        </v-stepper-header>

                        <v-stepper-items>
                            <v-stepper-content step="1">
                                <v-snackbar
                                        v-model="snackbar"
                                        color="error"
                                        :top="top"
                                        :timeout="timeout"
                                >
                                    {{snackbarMessage}}
                                    <v-btn
                                            dark
                                            flat
                                            @click="snackbar = false"
                                    >
                                        Close
                                    </v-btn>
                                </v-snackbar>
                                <vue-dropzone
                                        ref="mergePdfDropzone"
                                        id="drop1"
                                        :options="dropOptions"
                                        @vdropzone-success="afterComplete"
                                        @vdropzone-removed-file="fileRemoved"
                                        @vdropzone-error="uploadCancelled"
                                        class="white--text"
                                >
                                </vue-dropzone>

                                <v-btn
                                        class="red accent-2 white--text"
                                        @click="e1 = 2"
                                        v-if="uploadedFilesQuantity > 1 && uploadingFilesQuantity==0"
                                >
                                    Continue
                                </v-btn>
                            </v-stepper-content>

                            <v-stepper-content step="2">
                                <v-card
                                        class="mb-5"
                                        color="grey lighten-1"
                                        height="200px"
                                >
                                    <v-container fill-height>
                                        <v-layout align-center justify-center>
                                            <v-btn class="red accent-2 white--text" @click="downloadMergedPdfFile">
                                                <i class='fas fa-download'></i>
                                                Download Merged File
                                            </v-btn>
                                        </v-layout>
                                    </v-container>
                                </v-card>

                                <v-btn
                                        class="red accent-2 white--text"
                                        @click="convertMore"
                                >
                                    Convert more!
                                </v-btn>

                                <v-btn flat @click="e1 = 1">Cancel</v-btn>
                            </v-stepper-content>
                        </v-stepper-items>
                    </v-stepper>
                </v-flex>
                <v-flex xs12 sm3 md5 lg3 class="hidden-sm-and-down">
                    <aside>
                        <v-img src="images/banner_300x250.gif" alt="ad" width="300px" height="250px"></v-img>
                    </aside>
                </v-flex>
            </v-layout>
        </section>
        <aside class="hidden-md-and-up mt-5">
            <v-layout row wrap justify-center>
                <v-flex xs10 sm6 class="text-sm-center text-xs-center">
                    <div class="text-sm-center text-xs-center text-md-center">
                        <v-img src="images/banner_300x250.gif" alt="ad" width="300px" height="250px" class="ma-auto"></v-img>
                    </div>
                </v-flex>
            </v-layout>
        </aside>
        <section class="mt-5">
            <p class="title red--text text--accent-2">Other Services</p>
            <v-layout
                    row
                    wrap
                    justify-between
            >
                <v-flex xs12 sm9 md7 lg9>
                    <v-layout row wrap justify-between>
                        <v-flex xs12 sm6 md4 lg3 v-for="(item, item_id) in otherServices" :key="item_id" class="padding--ten">
                            <v-card hover tile :to="item.href" class="ma-1" height="100%">
                                <v-img :src="item.image" :alt="item.alt"></v-img>
                                <v-card-title class="dark-text font-weight-bold title">{{item.title}}</v-card-title>
                                <v-card-text class="secondary-text">
                                    {{item.content}}
                                </v-card-text>
                            </v-card>
                        </v-flex>
                    </v-layout>
                </v-flex>
                <v-flex class="hidden-sm-and-down" sm3 md5 lg3>
                    <aside>
                        <v-img src="images/banner_300x250.gif" alt="ad" width="300px" height="250px"></v-img>
                    </aside>
                </v-flex>
            </v-layout>
        </section>
    </main>
</template>

<script>
    import vueDropzone from "vue2-dropzone";
    import axios from 'axios';

    export default {
        data: () => ({
            items: [],
            currentLink: '/merge-pdf',
            uploadedFilesQuantity: 0,
            uploadingFilesQuantity: 0,
            snackbarMessage: "",
            snackbar: false,
            timeout: 2000,
            top: true,
            errors: [],
            scale: 'page-width',
            dir_names: [],
            folder_title: '',
            file_extension: {name:'.jpg', value:'1'},
            dropOptions: {
                url: '/api/store_pdf_for_merge',
                maxFilesize: 64,
                maxFiles: 20,
                acceptedFiles: '.pdf',
                timeout: 1500000,
                thumbnailHeight: 75,
                thumbnailWidth: 75,
                addRemoveLinks: true,
                dictDefaultMessage: `
                    <div>
                        <i class='fas fa-cloud-upload-alt'></i>
                    </div>
                    <div>
                        <p>Click here to upload files or just drop them here</p>
                    </div>`
            },
            e1:0,
        }),
        computed:{
            otherServices() {
                return this.items.filter(item => item.href!==this.currentLink)
            }
        },
        mounted: function () {
            this.items=this.$parent.$parent.items;
        },
        methods: {
            afterComplete(file) {
                this.dir_names.push(JSON.parse(file.xhr.responseText));
                this.uploadedFilesQuantity = this.$refs.mergePdfDropzone.getAcceptedFiles().length;
                this.uploadingFilesQuantity = this.$refs.mergePdfDropzone.getUploadingFiles().length + 
                                            this.$refs.mergePdfDropzone.getQueuedFiles().length;

            },
            fileRemoved(file,error,xhr){
                if(file||error||xhr){
                    let filteredArr=this.dir_names.filter(dir =>{
                        return dir.search(file.name)<0;
                    });
                    this.dir_names=filteredArr;
                    this.uploadedFilesQuantity = this.$refs.mergePdfDropzone.getAcceptedFiles().length;
                    this.uploadingFilesQuantity = this.$refs.mergePdfDropzone.getUploadingFiles().length + 
                                                this.$refs.mergePdfDropzone.getQueuedFiles().length;
                }
            },
            uploadCancelled(file, message, xhr){
                if(message || xhr){
                    if(!navigator.onLine){
                        this.snackbarMessage = "Internet is not connected";
                    }
                    else{
                        this.snackbarMessage = message;
                    }
                    this.snackbar = true;
                    this.$refs.mergePdfDropzone.removeFile(file);
                    this.uploadingFilesQuantity = this.$refs.mergePdfDropzone.getUploadingFiles().length +
                                                this.$refs.mergePdfDropzone.getQueuedFiles().length;
                }
            },
            downloadMergedPdfFile() {
                let formData = new FormData();
                let self=this;
                let title = JSON.stringify(this.dir_names);
                formData.append('directories',title);
                axios.post('/api/merge_pdf', formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                })
                    .then(function(data){
                        location.href="/api/download_merge_pdf/"+data.data;
                        self.el=0;
                    })
                    .catch(function(error){
                        console.log(error);
                    });
            },
            convertMore: function(){
                this.$refs.mergePdfDropzone.removeAllFiles();
                this.dir_names=[];
                this.e1=1;
            }
        },
        components: {
            vueDropzone,
        },
        metaInfo: {
            // Children can override the title.
            title: 'Merge PDF',
            // Result: Merge PDF ← PDFDad
            titleTemplate: '%s ← PDFDad',
            // Define meta tags here.
            meta: [
                {name: 'description', content: 'Merge multiple pdf files into one quickly and easily.'},

                // OpenGraph data (Most widely used)
                {property: 'og:title', content: 'Merge PDF ← PDFDad'},
                {property: 'og:site_name', content: 'PDFDad'},
                // The list of types is available here: http://ogp.me/#types
                {property: 'og:type', content: 'website'},
                {property: 'og:url', content: 'https://pdfdad.com/merge-pdf'},
                {property: 'og:image', content: 'https://pdfdad.com/images/merge_pdf.png'},
                // Often the same as your meta description, but not always.
                {property: 'og:description', content: 'Merge multiple pdf files into one quickly and easily.'},

                // Twitter card
                {name: 'twitter:card', content: 'summary'},
                {name: 'twitter:site', content: 'https://pdfdad.com/merge-pdf'},
                {name: 'twitter:title', content: 'Merge PDF ← PDFDad'},
                {name: 'twitter:description', content: 'Merge multiple pdf files into one quickly and easily.'},

                // Your twitter handle, if you have one.
                {name: 'twitter:creator', content: '@PDFDad01'},
                {name: 'twitter:image:src', content: 'https://pdfdad.com/images/merge_pdf.png'},

                // Google / Schema.org markup:
                {itemprop: 'name', content: 'Merge PDF ← PDFDad'},
                //.....
            ]
        }
    };
</script>

<style scoped>
    @font-face {
        font-family: Sanchez;
        src: url("~/fonts/Sanchezregular.otf");
    }
    .display-3{
        font-family: Sanchez;
    }
</style>