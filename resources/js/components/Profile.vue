<template>
     <section class="content">
      
      <div class="container-fluid">
         <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Profile</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="">Admin</a></li>
              <li class="breadcrumb-item active">Profile</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
        <div class="row">

          <div class="col-md-3">

            <!-- Profile Image -->
            <div class="card card-primary card-outline" >
              <div class="card-body box-profile" >
                <div class="text-center" >
                  <img class="profile-user-img img-fluid img-circle"
                       :src="getProfilePhoto()" 
                       alt="User profile picture">
                </div>

                <h3 class="profile-username text-center">Nina Mcintire</h3>

                <p class="text-muted text-center">Software Engineer</p>

                <ul class="list-group list-group-unbordered mb-3">
                  <li class="list-group-item">
                    <b>Followers</b> <a class="float-right">1,322</a>
                  </li>
                  <li class="list-group-item">
                    <b>Following</b> <a class="float-right">543</a>
                  </li>
                  <li class="list-group-item">
                    <b>Friends</b> <a class="float-right">13,287</a>
                  </li>
                </ul>

                <a href="#" class="btn btn-primary btn-block"><b>Follow</b></a>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->

            <!-- /.card -->
          </div>
          <!-- /.col -->
          <div class="col-md-9">
            <div class="card">
              <div class="card-header p-2">
                <ul class="nav nav-pills">
                  <li class="nav-item"><a class="nav-link" href="#activity" data-toggle="tab">Activity</a></li>
                 <li class="nav-item"><a class="nav-link active" href="#settings" data-toggle="tab">Settings</a></li>
                  <li class="nav-item"><a class="nav-link" href="#timeline" data-toggle="tab">Timeline</a></li>
                </ul>
              </div><!-- /.card-header -->
              <div class="card-body">
                <div class="tab-content">
                  <div class="tab-pane" id="activity"> <p style="text-align:center; font-size:25px;">Display Activity</p> </div>
                  <!-- /.tab-pane -->
                  
                  <!-- /.tab-pane -->

                  <div class="active tab-pane" id="settings">
                    <form class="form-horizontal" @submit.prevent="updateUser()">
                      <div class="form-group row">
                        <label for="inputName" class="col-sm-2 col-form-label">Name</label>
                        <div class="col-sm-10">
                          <input v-model="form.name" type="text" name="name" placeholder="Name" class="form-control" :class="{ 'is-invalid': form.errors.has('name') }">
                          <has-error :form="form" field="name"></has-error>
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="inputEmail" class="col-sm-2 col-form-label">Email</label>
                        <div class="col-sm-10">
                          <input v-model="form.email" type="text" name="email" placeholder="Email" class="form-control" :class="{ 'is-invalid': form.errors.has('email') }">
                          <has-error :form="form" field="email"></has-error>
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="inputExperience" class="col-sm-2 col-form-label">Bio</label>
                        <div class="col-sm-10">
                         <textarea v-model="form.bio" type="text" name="bio" placeholder="Short Bio For User (Optional)" class="form-control" :class="{ 'is-invalid': form.errors.has('bio') }"></textarea>
                         <has-error :form="form" field="bio"></has-error>
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="inputSkills" class="col-sm-2 col-form-label">Photo</label>
                         <input type="file" @change="updateProfile"  class="" id="myFile" name="filename" :class="{ 'is-invalid': form.errors.has('photo') }">
                         <has-error :form="form" field="photo"></has-error>
                      </div>
                       <div class="form-group row">
                        <label for="inputName" class="col-sm-2 col-form-label">Password</label>
                        <div class="col-sm-10">
                          <input v-model="form.password" type="password" autocomplete="off" name="password" placeholder="Password" id="password" class="form-control" :class="{ 'is-invalid': form.errors.has('password') }">
                          <has-error :form="form" field="password"></has-error>
                        </div>
                      </div>
                      <div class="form-group row">
                        <div class="offset-sm-2 col-sm-10">
                          <div class="checkbox">
                            <label>
                              <input type="checkbox"> I agree to the <a href="#">terms and conditions</a>
                            </label>
                          </div>
                        </div>
                      </div>
                      <div class="form-group row">
                        <div class="offset-sm-2 col-sm-10">
                          <button @click.prevent="updateUser()" type="submit" class="btn btn-danger">Submit</button>
                        </div>
                      </div>
                    </form>
                  </div>
                  <!-- /.tab-pane -->

                   <!-- /.tab-pane -->
                    <div class="tab-pane" id="timeline">
                    <!-- The timeline -->
                   <p style="text-align:center; font-size:25px;">Display Timeline</p>
                  </div>
                   <!-- /.tab-pane -->

                </div>
                <!-- /.tab-content -->
              </div><!-- /.card-body -->
            </div>
            <!-- /.nav-tabs-custom -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>
</template>

<script>
    export default {

         data() {
          return {
            editmode: false,
            
            form: new Form({
              id:'',
              name: '',
              email: '',
              password: '',
              type: '',
              bio: '',

            })
          }
        },

         methods: {



            disableAutoComplete() {
          let elements = document.querySelectorAll('[autocomplete="off"]');

              if (!elements) {
                return;
              }

              elements.forEach(element => {
                  element.setAttribute("readonly", "readonly");
                  element.style.backgroundColor = "inherit";

                  setTimeout(() => {
                      element.removeAttribute("readonly");
                   }, 500);
              });
          },
      





            loadUsers(){
              this.form.reset();
              axios.get("api/profile").then( ({data}) => (  this.form.fill(data)) );
              this.form.photo = 'empty';
              this.disableAutoComplete();
            },
            
            getProfilePhoto(){
              return "img/profile/"+this.form.photo;
            },

             updateUser(){
              //this.$Progress.start();
              this.$Progress.start();
              this.form.patch('api/profile').then(()=>{
                 //$('#adduser').modal('hide');
                 Toast.fire({
                      icon: 'success',
                      title: 'User Created Successfully'
                    });
                 this.loadUsers();
                 this.form.reset();
              
                 this.$Progress.finish();
               })

              .catch(()=>{
                Toast.fire({
                      icon: 'error',
                      title: 'Failed Unable To Create User'
                    });
                this.$Progress.fail();
              })
             },

             updateProfile(e){
              //console.log('uploading');
                 this.$Progress.start();
              
                let file = e.target.files[0];
                console.log(file);
                let reader = new FileReader();
                
                if (file['size'] < 2111775) {
                  
                  reader.onloadend = (file)=> {
                    //console.log('RESULT', reader.result)
                    this.form.photo = reader.result;
                     this.$Progress.finish();
              
                  }
                  reader.readAsDataURL(file);
                }else{
                  Toast.fire({
                      icon: 'error',
                      title: 'File Size Should Be Less Than 2MB'
                    });
                  this.$Progress.fail();
                }
                
             },

             
            
        },

        mounted() {
            console.log('Component mounted.');
            this.$Progress.start();
            this.loadUsers();
            this.$Progress.finish();
            //this.disableAutoComplete();
            
        },
    }

</script>
