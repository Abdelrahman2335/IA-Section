<?php

namespace App;

interface HomeInterface
{
    public function getCourses(): array;

    public function getUserCourses(): ?array;

    public function addCourse(): void;


    public function updateUserCourse(): void;

    public function deleteCourse(): void;
}