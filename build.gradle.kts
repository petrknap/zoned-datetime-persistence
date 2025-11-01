plugins {
    `java-library`
}

repositories {
    mavenCentral()
}

dependencies {
    implementation("jakarta.persistence:jakarta.persistence-api:3.1.0")
    testImplementation("org.xerial:sqlite-jdbc:3.50.3.0")
    testImplementation("org.hibernate.orm:hibernate-core:6.6.34.Final")
    testImplementation("org.hibernate.orm:hibernate-community-dialects:6.6.34.Final")
    testImplementation(libs.junit.jupiter)
    testRuntimeOnly("org.junit.platform:junit-platform-launcher")
}

java {
    toolchain {
        languageVersion = JavaLanguageVersion.of(11)
    }
}

tasks.named<Test>("test") {
    useJUnitPlatform()
}
