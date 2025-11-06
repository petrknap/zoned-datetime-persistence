import java.io.ByteArrayOutputStream

val gitTag = ByteArrayOutputStream()
exec {
    commandLine = listOf("git", "describe", "--tags", "--abbrev=0")
    isIgnoreExitValue = true
    standardOutput = gitTag
}
val gitBranch = ByteArrayOutputStream()
exec {
    commandLine = listOf("git", "rev-parse", "--abbrev-ref", "HEAD")
    standardOutput = gitBranch
}

description = "Timezone aware date-time persistence"
group = "io.github.petrknap"
version = gitTag.toString().trim()
if (version == "") {
    version = (gitBranch.toString().trim() + "-SNAPSHOT").removePrefix("-")
}

plugins {
    `java-library`
    `maven-publish`
}

repositories {
    mavenCentral()
}

dependencies {
    implementation(libs.jakarta.persistence.api)
    testImplementation(libs.hibernate.core)
    testImplementation(libs.hibernate.dialects)
    testImplementation(libs.junit.jupiter)
    testImplementation(libs.sqlite.jdbc)
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

publishing {
    publications {
        create<MavenPublication>("maven") {
            from(components["java"])

            pom {
                url = "https://github.com/petrknap/zoned-datetime-persistence"
                developers {
                    val authorsFile = file("AUTHORS")
                    if (authorsFile.exists()) {
                        authorsFile.readLines().forEach { line ->
                            val regex = Regex("(.*) <(.*)>")
                            val match = regex.matchEntire(line)
                            if (match != null) {
                                developer {
                                    name.set(match.groupValues[1].trim())
                                    email.set(match.groupValues[2].trim())
                                }
                            }
                        }
                    }
                }
            }
        }
    }
}
