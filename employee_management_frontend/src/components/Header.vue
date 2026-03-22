<template>
  <div class="home">
    <div class="hero-banner">
      <div class="header-content">
        <h1 class="headingOne">
          Employee Managment System</h1>

        <!-- Action Buttons -->
        <div class="buttons">
          <router-link to="/NewEmployee" class="btn"
            >Add New Client</router-link
          >
          <router-link to="/AllEmployee" class="btn2"
            >View Clients</router-link
          >
        </div>
      </div>

    </div>
  </div>
</template>

<script>
export default {
  name: "Header",
};
</script>

<style scoped lang="scss">
.home {
  background-color: #3d5a80;
  .hero-banner {
    display: flex;
    justify-content: space-between;
    align-items: center;
    height: 600px;
    padding: 0 10%;
    padding-top: 30px;

    @media (max-width: 950px) {
      flex-direction: column;
      height: auto;
      padding: 0 5%;
    }

    .header-content {
      flex: 1;
      flex-direction: column;
      justify-content: center;
      color: white;

      .headingOne {
        font-size: 48px;
        font-family: "Prata", serif;
        margin-bottom: 15px;
        color: #ffffff;

        .highlight {
          color: #9b0ead;
        }
        @media (max-width: 768px) {
          font-size: 32px;
          text-align: center;
        }
      }

      .product_heading {
        font-size: 24px;
        font-family: "Prata", serif;
        margin-bottom: 25px;

        @media (max-width: 768px) {
          font-size: 18px;
          text-align: center;
        }
      }

      .buttons {
        display: flex;
        gap: 20px;

        @media (max-width: 900px) {
          flex-direction: column;
          align-items: center;
        }

        .btn, .btn2 {
          padding: 10px 25px;
          background-color: #259e06;
          color: white;
          font-size: 16px;
          text-decoration: none;
          border-radius: 5px;
          transition: transform 0.2s, background-color 0.3s ease;

          &:hover {
            transform: scale(1.25);
            background-color: #8f0069;
          }
        }
      }
    }
  }
}
</style>
